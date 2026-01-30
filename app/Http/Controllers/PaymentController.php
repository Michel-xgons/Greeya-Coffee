<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;

class PaymentController extends Controller
{
    public function createInvoice(Request $request)
    {
        $customerData = $request->customer;
        $cart = $request->cart;

        if (!$customerData || !$cart || empty($cart['items'])) {
            return response()->json([
                'message' => 'Data tidak lengkap'
            ], 422);
        }

        $biayaLain = 170;
        $subtotal = $cart['totalHarga'];
        $totalBayar = $subtotal + $biayaLain;

        DB::beginTransaction();

        try {
            /** 1. SIMPAN / AMBIL CUSTOMER */
            $customer = Customer::firstOrCreate(
                ['phone' => $customerData['phone']],
                [
                    'name'  => $customerData['name'],
                    'email' => $customerData['email']
                ]
            );

            /** 2. SIMPAN PESANAN */
            $pesanan = Pesanan::create([
                'id_customer'   => $customer->id,
                'kode_pesanan'  => 'ORD-' . time(),
                'total_harga'   => $totalBayar,
                'status_pesanan'=> 'PENDING'
            ]);

            /** 3. SIMPAN DETAIL PESANAN */
            foreach ($cart['items'] as $item) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id,
                    'id_menu'   => $item['id'] ?? null,
                    'jumlah'    => $item['qty'],
                    'harga'     => $item['price'],
                    'subtotal'  => $item['qty'] * $item['price']
                ]);
            }

            /** 4. BUAT INVOICE XENDIT */
            Configuration::setXenditKey(config('services.xendit.secret_key'));
            $apiInstance = new InvoiceApi();

            $invoice = $apiInstance->createInvoice([
                'external_id'  => $pesanan->kode_pesanan,
                'amount'       => $totalBayar,
                'payer_email'  => $customer->email,
                'description'  => 'Pembayaran Greeya Coffee'
            ]);

            /** 5. UPDATE PESANAN */
            $pesanan->update([
                'xendit_invoice_id' => $invoice['id']
            ]);

            DB::commit();

            return response()->json([
                'invoice_url' => $invoice['invoice_url']
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal membuat invoice',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
