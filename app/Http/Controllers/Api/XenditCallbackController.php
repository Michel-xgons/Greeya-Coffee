<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class XenditCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        Log::info('Xendit Callback:', $request->all());

        $externalId = $request->external_id ?? null;
        $status = strtoupper($request->status ?? '');

        if (!$externalId) {
            return response()->json(['message' => 'Invalid callback'], 400);
        }

        try {
            DB::beginTransaction();

            // Find or create transaction
            $transaction = Pembayaran::where('xendit_external_id', $externalId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            $transaction->transaction_status = strtoupper($status);


            if ($transaction->pesanan) {

                if ($status === 'PAID') {

                    $transaction->pesanan->payment_status = 'paid';
                    $transaction->transaction_time = now();
                } elseif ($status === 'EXPIRED') {

                    $transaction->pesanan->payment_status = 'expired';
                } else {

                    $transaction->pesanan->payment_status = 'pending';
                }

                $transaction->pesanan->save();
            }

            $transaction->save();

            DB::commit();

            Log::info('Xendit Callback Order Updated:', $transaction->toArray());
            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Xendit Callback Error:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
