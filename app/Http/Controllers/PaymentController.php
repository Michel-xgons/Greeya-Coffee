<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pesanan;
use Illuminate\Support\Str;
use App\Models\Pembayaran;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;



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

        // if (!session()->has('customer_id')) {
        //     return redirect()->route('Pemesanan')
        //         ->with('error', 'Silakan isi data pemesan.');
        // }

        return view('frontend.payment');
    }

    public function __construct(XenditService $xendit)
    {
        $this->xendit = $xendit;
    }


   public function createInvoice(Request $request)
{
    DB::beginTransaction();

    try {

        // CUSTOMER
        $customerData = $request->input('customer');

        if (
            !$customerData ||
            empty($customerData['name']) ||
            empty($customerData['email']) ||
            empty($customerData['phone'])
        ) {
            return back()->with('error', 'Data customer tidak lengkap');
        }

        $customer = Customer::firstOrCreate(
            ['no_telpon' => $customerData['phone']],
            [
                'name'  => $customerData['name'],
                'email' => $customerData['email'],
            ]
        );

        // CART
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Cart kosong');
        }

        // MEJA
        if (!session()->has('nomor_meja')) {
            return back()->with('error', 'Meja belum dipilih');
        }

        $meja = session('nomor_meja');

        // TOTAL
        $totalHarga = 0;

        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
        }

        // PESANAN
        $pesanan = Pesanan::create([
            'kode_pesanan'   => 'ORD-' . Str::uuid(),
            'customer_id'    => $customer->id,
            'meja_id'        => $meja,
            'waktu_pesan'    => now(),
            'payment_status' => 'pending',
            'catatan'        => 'Pesanan dari checkout',
            'total_harga'    => $totalHarga
        ]);

        // DETAIL
        foreach ($cart as $item) {
            $pesanan->detailPesanans()->create([
                'menu_id'    => $item['menu_id'],
                'variant_id' => $item['variant_id'] ?? null,
                'note'       => $item['note'] ?? null,
                'subtotal'   => $item['harga'] * $item['qty'],
                'jumlah'     => $item['qty'],
                'harga'      => $item['harga'],
            ]);
        }

        // XENDIT
        $pembayaran = $this->xendit->createQrisTransaction($pesanan);

        DB::commit();

        return redirect($pembayaran->invoice_url);

    } catch (\Exception $e) {
        DB::rollBack();
        dd($e->getMessage()); // 🔥 biar error kelihatan jelas
    }
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
        // kalau sudah dibayar
        if ($pesanan->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah dibayar');
        }

        // 🔥 TAMBAHKAN DI SINI
        if ($pesanan->pembayaran && $pesanan->pembayaran->transaction_status === 'pending') {
            return redirect($pesanan->pembayaran->invoice_url);
        }

        // kalau belum ada / sudah expired → buat baru
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
