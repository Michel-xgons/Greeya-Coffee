<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class RiwayatPesananController extends Controller
{
    public function index(Request $request)
{
    $phone = $request->phone;

    if (!$phone) {
        return redirect()->route('Branda')
            ->with('error', 'Nomor tidak ditemukan');
    }

    $riwayat = Pesanan::with([
        'customer',
        'detailPesanans.menu',
        'pembayaran'
    ])
    ->whereHas('customer', function ($q) use ($phone) {
        $q->where('no_telpon', $phone);
    })
    ->latest()
    ->get();

    return view('frontend.riwayat.index', compact('riwayat', 'phone'));
}

    public function getRiwayat(Request $request)
{
    $status = $request->status;
    $phone = $request->phone;

    $query = Pesanan::with([
        'customer',
        'detailPesanans.menu',
        'pembayaran'
    ])
    ->whereHas('customer', function ($q) use ($phone) {
        $q->where('no_telpon', $phone);
    });

    if ($status && $status != 'all') {
        $query->whereHas('pembayaran', function ($q) use ($status) {
            $q->where('transaction_status', $status);
        });
    }

    $riwayat = $query->latest()->get();

    $html = view('frontend.riwayat._list', compact('riwayat'))->render();

    return response()->json([
        'html' => $html
    ]);
}
}
