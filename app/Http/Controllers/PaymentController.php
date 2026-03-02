<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PaymentService;


class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
    $this->paymentService = $paymentService;
    }

    public function createInvoice(Request $request)
    {
        // dd($request);

        $validated = $request->validate([
            'customer.name' => 'required|string|max:100',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string',
        ]);

        $validated($validated);
        
        $result = $this->paymentService->createInvoice($validated);

        return response()->json($result);
    }

    public function webhook(Request $request)
    {
        return $this->paymentService->handleWebhook($request);
    }
}
