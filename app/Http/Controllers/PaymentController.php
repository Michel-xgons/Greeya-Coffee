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

        if (!session()->has('customer')) {
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
        // dd($request);

        $request->validate([
            'nama'          => 'required|string|max:100',
            'email'         => 'required|email',
            'telepon'       => 'required|string',
        ]);

        session()->put('phone', $request->telepon);

        $meja = session('meja_id');

        // Hitung total dari cart
        $cart = session('cart', []);


        $totalHarga = 0;
        $totalItem = 0;

        foreach ($cart as $item) {
            $totalHarga += $item['harga'] * $item['qty'];
            $totalItem  += $item['qty'];
        }

        $pesanan = Pesanan::with('customer')
            ->whereHas('customer', function ($query) use ($request) {
                $query->where('no_telpon', $request->telepon);
            })
            ->where('payment_status', 'unpaid')
            ->first();

        if ($pesanan) {
            return redirect(route('riwayat.pesanan'))->with('error', 'Anda sudah memiliki pesanan yang belum dibayar');
        }

        //simpan data user
        $customer = Customer::firstOrCreate(
            ['no_telpon' => $request->telepon],
            [
                'name'  => $request->nama,
                'email' => $request->email,
            ]
        );

        // simpan pesanan ke database
        $pesanan = Pesanan::create([
            'kode_pesanan'          => 'ORD-' . Str::uuid(),
            'customer_id'           => $customer->id,
            'meja_id'               => $meja,
            'waktu_pesan'           => now(),
            'payment_status'        => 'unpaid',
            'catatan'               => 'Test pesanan',
            'total_harga'           => $totalHarga
        ]);

        // simpan order items
        foreach ($cart as $item) {
            $pesanan->detailPesanans()->create([
                'pesanan_id'        => $pesanan->id,
                'menu_id'           => $item['id'],
                'varian'            => $item['variant'] ?? null,
                'subtotal'         => $item['harga'] * $item['qty'],
                'jumlah'            => $item['qty'],
                'harga'             => $item['harga'],
            ]);
        }

        $pembayarans = (new XenditService())->createQrisTransaction($pesanan);

        return redirect($pembayarans->invoice_url);
    }

    public function success(Request $request)
    {
        session()->forget('cart');

        return redirect(route('riwayat.pesananuser'))->with('success', 'Payment success');
    }

    public function failed(Request $request)
    {
        return redirect(route('history.order'))->with('error', 'Payment failed');
    }

    public function payAgain(Pesanan $pesanan)
    {
        if ($pesanan->payment_status === 'paid') {
            return redirect()->back()->with('error', 'Pesanan sudah dibayar');
        }

        $pembayarans = $this->xendit->createQrisTransaction($pesanan);

        return redirect($pembayarans->invoice_url);
    }

    public function webhook(Request $request)
{
    $data = $request->all();

    Log::info('Webhook Xendit:', $data);

    $externalId = $data['external_id'] ?? null;
    $status     = $data['status'] ?? null;

    Log::info('EXTERNAL ID MASUK: ' . $externalId);
    
    if (!$externalId) {
        return response()->json(['message' => 'external_id tidak ada'], 400);
    }

    $pembayaran = Pembayaran::where('xendit_external_id', $externalId)->first();

    if (!$pembayaran) {
        return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
    }

    if ($status === 'PAID') {

        $pembayaran->transaction_status = 'PAID';
        $pembayaran->save();

        $pesanan = Pesanan::find($pembayaran->pesanan_id);
        $pesanan->payment_status = 'paid';
        $pesanan->save();
    }

    if ($status === 'EXPIRED') {

        $pembayaran->transaction_status = 'EXPIRED';
        $pembayaran->save();

        $pesanan = Pesanan::find($pembayaran->pesanan_id);
        $pesanan->payment_status = 'expired';
        $pesanan->save();
    }

    return response()->json(['success' => true]);
}
}
