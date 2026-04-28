<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Services\XenditService;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


class PemesananController extends Controller
{
    public function index()
    {
        if (session()->has('customer_data')) {
            return redirect()->route('checkout.auto');
        }

        return view('frontend.Menu.Pemesanan');
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'customer.name'  => 'required|string|max:100',
            'customer.email' => 'required|email',
            'customer.phone' => 'required'
        ]);

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('Branda')
                ->with('error', 'Cart kosong');
        }

        $meja = session('nomor_meja');

        if (!$meja) {
            return redirect()->route('Branda')
                ->with('error', 'Meja belum dipilih');
        }

        $phone = preg_replace('/[^0-9]/', '', $request->input('customer.phone'));

        $totalHarga = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty'] + 4000);

        DB::beginTransaction();

        try {
            $customer = Customer::firstOrCreate(
                ['no_telpon' => $phone],
                [
                    'name'  => $request->input('customer.name'),
                    'email' => $request->input('customer.email'),
                ]
            );

            session([
                'customer_data' => [
                    'name'  => $customer->name,
                    'email' => $customer->email,
                    'phone' => $customer->no_telpon,
                ]
            ]);

            $pesanan = Pesanan::create([
                'kode_pesanan'   => 'ORD-' . Str::uuid(),
                'customer_id'    => $customer->id,
                'meja_id'        => $meja,
                'waktu_pesan'    => now(),
                'payment_status' => 'PENDING',
                'catatan'        => 'Pesanan dari checkout',
                'total_harga'    => $totalHarga
            ]);

            foreach ($cart as $item) {
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'menu_id'    => $item['menu_id'],
                    'variant'    => $item['variant'] ?? null,
                    'jumlah'     => $item['qty'],
                    'harga'      => $item['harga'],
                    'subtotal'   => $item['harga'] * $item['qty'],
                    'note'       => $item['note'] ?? null,
                ]);
            }

            $payment = app(XenditService::class)
                ->createQrisTransaction($pesanan);

            DB::commit();

            session()->forget('cart');

            return redirect($payment->invoice_url);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan');
        }
    }

    public function auto()
    {
        if (empty(session('cart'))) {
            return redirect()->route('Branda')
                ->with('error', 'Cart kosong');
        }

        if (!session()->has('customer_data')) {
            return redirect()->route('Pemesanan');
        }

        $customer = session('customer_data');

        request()->merge([
            'customer' => [
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ]
        ]);

        return $this->simpan(request());
    }
}
