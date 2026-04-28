<?php

namespace App\Http\Controllers;
use App\Models\Pesanan;
use App\Models\Pembayaran;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected XenditService $xendit;

    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Keranjang kosong.');
        }

        return view('frontend.payment');
    }

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }

    public function success()
    {
        return redirect()->route('riwayat.pesanan');
    }

    public function failed(Request $request)
    {
        return redirect()->route('Branda')->with('error', 'Payment failed');
    }

    public function payAgain(Pesanan $pesanan)
    {
        if ($pesanan->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah dibayar');
        }

        if ($pesanan->pembayaran && $pesanan->pembayaran->transaction_status === 'pending') {
            return redirect($pesanan->pembayaran->invoice_url);
        }

        $pembayarans = $this->xendit->createQrisTransaction($pesanan);

        return redirect($pembayarans->invoice_url);
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanans.menu', 'pembayaran'])
            ->where('id', $id)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->firstOrFail();

        if ($pesanan->payment_status == 'paid') {
            session()->forget('cart');
        }

        return view('payment.show', compact('pesanan'));
    }

    public function webhook(Request $request)
    {

        $callbackToken = $request->header('x-callback-token');

        if ($callbackToken !== config('services.xendit.callback_token')) {
            return response()->json(['message' => 'Invalid token'], 403);
        }

        $data = $request->all();

        Log::info('Webhook Xendit:', $data);

        $externalId = $data['external_id'] ?? null;
        $status     = $data['status'] ?? null;

        if (!$externalId) {
            return response()->json(['message' => 'external_id tidak ada'], 400);
        }

        $pembayaran = Pembayaran::where('xendit_external_id', $externalId)->first();

        if (!$pembayaran) {
            return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
        }

        $pesanan = Pesanan::find($pembayaran->pesanan_id);

        if (!$pesanan) {
            Log::error('Pesanan tidak ditemukan', [
                'pesanan_id' => $pembayaran->pesanan_id
            ]);
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        if ($status === 'PAID' && $pembayaran->transaction_status !== 'PAID') {

            $pembayaran->update([
                'transaction_status' => 'PAID',
                'transaction_time' => isset($data['paid_at'])
                    ? \Carbon\Carbon::parse($data['paid_at'])
                    : now()
            ]);

            $pesanan->update([
                'payment_status' => 'paid'
            ]);
        }

        if ($status === 'EXPIRED' && $pembayaran->transaction_status !== 'EXPIRED') {

            $pembayaran->update([
                'transaction_status' => 'EXPIRED'
            ]);

            $pesanan->update([
                'payment_status' => 'expired'
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function cekStatus($id)
    {
        $pesanan = \App\Models\Pesanan::find($id);

        if (!$pesanan) {
            return response()->json([
                'status' => 'not_found'
            ], 404);
        }

        return response()->json([
            'status' => $pesanan->payment_status
        ]);
    }
}
