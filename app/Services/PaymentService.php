<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Pembayaran;
use App\Models\Menu;
use App\Models\Menus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class PaymentService
{
    public function createInvoice(array $data): array
    {
        DB::beginTransaction();

        try {

            // 1️⃣ Hitung ulang harga dari database
            $subtotal = 0;
            $itemsData = [];

            foreach ($data['cart']['items'] as $item) {
                $menu = Menus::findOrFail($item['id']);

                $itemSubtotal = $menu->harga * $item['qty'];
                $subtotal += $itemSubtotal;

                $itemsData[] = [
                    'menu' => $menu,
                    'qty' => $item['qty'],
                    'subtotal' => $itemSubtotal
                ];
            }

            $biayaLain = 170;
            $totalBayar = $subtotal + $biayaLain;

            // 2️⃣ Customer
            $customer = Customer::firstOrCreate(
                ['phone' => $data['customer']['phone']],
                [
                    'name'  => $data['customer']['name'],
                    'email' => $data['customer']['email']
                ]
            );

            // 3️⃣ Pesanan
            $pesanan = Pesanan::create([
                'id_customer' => $customer->id,
                'kode_pesanan' => 'ORD-' . Str::uuid(),
                'total_harga' => $totalBayar,
                'status_pesanan' => 'PENDING'
            ]);

            // 4️⃣ Detail
            foreach ($itemsData as $item) {
                DetailPesanan::create([
                    'id_pesanan' => $pesanan->id,
                    'id_menu' => $item['menu']->id,
                    'jumlah' => $item['qty'],
                    'harga' => $item['menu']->harga,
                    'subtotal' => $item['subtotal']
                ]);
            }

            // 5️⃣ Create Invoice Xendit
            Configuration::setXenditKey(config('services.xendit.secret_key'));

            $apiInstance = new InvoiceApi();

            $invoiceRequest = new CreateInvoiceRequest([
                'external_id' => $pesanan->kode_pesanan,
                'amount' => $totalBayar,
                'payer_email' => $customer->email,
                'description' => 'Pembayaran Greeya Coffee',
                'currency' => 'IDR',
                'invoice_duration' => 86400,
            ]);

            $invoice = $apiInstance->createInvoice($invoiceRequest);

            // // 6️⃣ Simpan ke tabel pembayarans
            Pembayaran::create([
                'pesanan_id' => $pesanan->id,
                'external_id' => $pesanan->kode_pesanan,
                'kode_pembayaran' => 'PAY-' . strtoupper(Str::random(10)),
                'xendit_invoice_id' => $invoice['id'],
                'invoice_url' => $invoice['invoice_url'],
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'pending',
            ]);

            DB::commit();

            return [
                'invoice_url' => $invoice['invoice_url']
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    public function handleWebhook($request)
{
    $callbackToken = $request->header('x-callback-token');

    if ($callbackToken !== config('services.xendit.callback_token')) {
        return response()->json(['message' => 'Invalid token'], 403);
    }

    $payload = $request->all();

    $pembayaran = Pembayaran::where(
        'xendit_invoice_id',
        $payload['id'] ?? null
    )->first();

    if (!$pembayaran) {
        return response()->json(['message' => 'Pembayaran tidak ditemukan'], 404);
    }

    // Hindari double processing
    if ($pembayaran->status_pembayaran === 'paid') {
        return response()->json(['message' => 'Already processed']);
    }

    DB::transaction(function () use ($pembayaran, $payload) {

        if ($payload['status'] === 'PAID') {

            $pembayaran->update([
                'status_pembayaran' => 'paid',
                'waktu_bayar' => now(),
                'metode_pembayaran' => $payload['payment_method'] ?? 'unknown',
                'callback_payload' => $payload,
            ]);

            $pembayaran->pesanan->update([
                'status_pesanan' => 'PAID'
            ]);
        }

        if ($payload['status'] === 'EXPIRED') {

            $pembayaran->update([
                'status_pembayaran' => 'expired',
                'callback_payload' => $payload,
            ]);

            $pembayaran->pesanan->update([
                'status_pesanan' => 'EXPIRED'
            ]);
        }
    });

    return response()->json(['success' => true]);
}
}
