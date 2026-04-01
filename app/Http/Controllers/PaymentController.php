<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pesanan;
use Illuminate\Support\Str;
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

        if (!session()->has('customer_id')) {
            return redirect()->route('Pemesanan')
                ->with('error', 'Silakan isi data pemesan.');
        }

        return view('frontend.payment');
    }

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }


    public function createInvoice(Request $request)
    {
        
        // 1. VALIDASI
        $request->validate([
            'nama'    => 'required|string|max:100',
            'email'   => 'required|email',
            'telepon' => 'required|string',
        ]);

        // 2. AMBIL / BUAT CUSTOMER
        $customer = Customer::firstOrCreate(
            ['no_telpon' => $request->telepon],
            [
                'name'  => $request->nama,
                'email' => $request->email,
            ]
        );

        session([
            'customer_id' => $customer->id
        ]);


        // 3. CEK ORDER PENDING
        $existingOrder = Pesanan::where('customer_id', $customer->id)
            ->where('payment_status', 'pending')
            ->latest()
            ->first();

        if ($existingOrder) {
            return redirect()->route('payment.show', $existingOrder->id);
        }

        // 4. AMBIL CART
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Cart kosong');
        }


        // 5. MEJA
        $meja = session('meja_id');

        if (!$meja) {
            return redirect('/')
                ->with('error', 'Meja belum dipilih');
        }

        // 6. HITUNG TOTAL
        $totalHarga = 0;

        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
        }

        // 7. BUAT PESANAN
        $pesanan = Pesanan::create([
            'kode_pesanan'   => 'ORD-' . Str::uuid(),
            'customer_id'    => $customer->id,
            'meja_id'        => $meja,
            'waktu_pesan'    => now(),
            'payment_status' => 'pending',
            'catatan'        => 'Pesanan dari checkout',
            'total_harga'    => $totalHarga
        ]);

        // 8. DETAIL PESANAN
        foreach ($cart as $item) {
            $pesanan->detailPesanans()->create([
                'menu_id'   => $item['id'],
                'varian'    => $item['varian'] ?? null,
                'note'      => $item['note'] ?? null,
                'subtotal'  => $item['harga'] * $item['qty'],
                'jumlah'    => $item['qty'],
                'harga'     => $item['harga'],
            ]);
        }

        // 9. BUAT INVOICE

        $pembayaran = $this->xendit->createQrisTransaction($pesanan);

        return redirect($pembayaran->invoice_url);
    }

    public function success()
    {
        return redirect()->route('riwayat.pesanan');
    }

    public function failed(Request $request)
    {
        return redirect(route(''))->with('error', 'Payment failed');
    }

    public function payAgain(Pesanan $pesanan)
    {
        if ($pesanan->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah dibayar');
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

        if ($status === 'PAID') {

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

        if ($status === 'EXPIRED') {

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
