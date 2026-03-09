<?php

namespace App\Services;

use App\Models\Pesanan;
use App\Models\Pembayaran;
use Faker\Provider\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected $apiKey;

    // public function __construct()
    // {
    //     $this->apiKey = config('services.xendit.api_key');
    // }
    public function __construct()
    {
        $this->apiKey = config('services.xendit.secret_key');
    }

    /**
     * Buat QRIS Transaction
     */
    public function createQrisTransaction(Pesanan $pesanan)
    {
        $externalId = 'ORD-' . $pesanan->id . '-' . Str::uuid();
        $grossAmount = $pesanan->total_harga + 4000;
        $payload = [
            'external_id' => $externalId,
            'amount' => $grossAmount,
            'description' => 'Pembayaran Order #' . $externalId,
            'currency' => 'IDR',
            'invoice_duration' => 600,
            'customer' => [
                'given_names' => $pesanan->customer->nama ?? 'Customer',
                'email' => $pesanan->customer->email ?? 'customer@example.com',
                'mobile_number' => $pesanan->customer->phone ?? '08123456789',
            ],
            'customer_notification_preference' => [
                'invoice_created' => ['email'],
                'invoice_reminder' => ['email'],
                'invoice_paid' => ['email'],
                'invoice_expired' => ['email'],
            ],
            'success_redirect_url' => url('/payment-success'),
            'failure_redirect_url' => url('/payment-failure'),
            'items' =>

            $pesanan->detailPesanans->map(function ($item) {
                return [
                    'id'        => $item->id,
                    'price'     => $item->harga, 
                    'quantity'  => $item->jumlah,
                    'name'      => $item->menu->nama_menu ?? 'Menu',
                ];
            })->toArray(),

            'fees' => [
                [
                    'type' => 'PPN',
                    'value' => 4000,
                ],
            ],
            'payment_methods' => ['QRIS'],
            'metadata' => [
                'order_id' => $pesanan->id,
            ],
        ];
        $headers = [
            'api-version' => "2022-07-31",
            'Content-Type' => 'application/json',
        ];

        //         dd([
        //     'env' => env('XENDIT_SECRET_KEY'),
        //     'config' => config('services.xendit.secret_key'),
        // ]);
        $response = Http::withBasicAuth($this->apiKey, '')
            ->withHeaders($headers)
            ->post('https://api.xendit.co/v2/invoices', $payload);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed()) {
            throw new \Exception('Xendit API error: ' . $response->body());
        }
        $result = $response->json();
        Log::info('Xendit Response:', $result);
        $expiry = null;
        if (!empty($result['expiry_date'])) {
            $expiry = \Carbon\Carbon::parse($result['expiry_date'])
                ->setTimezone('Asia/Jakarta')
                ->format('Y-m-d H:i:s');
        }

        return Pembayaran::create([
            'pesanan_id' => $pesanan->id,
            'xendit_external_id' => $externalId,
            'payment_type' => 'qris',
            'transaction_status' => 'PENDING',
            'gross_amount' => $grossAmount,
            'invoice_url' => $result['invoice_url'] ?? null,
            'expiry_time' => $expiry
        ]);
    }
}
