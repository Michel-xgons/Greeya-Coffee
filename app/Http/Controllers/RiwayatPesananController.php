<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        $customer_id = session('customer_id');

        if (!$customer_id) {
            return redirect()->back()->with('error', 'Session hilang');
        }

        $riwayat = Pesanan::with(['customer', 'detailPesanans.menu'])
            ->where('customer_id', $customer_id)
            ->latest()
            ->get();

        return view('frontend.riwayat.index', compact('riwayat'));
    }

    public function getRiwayat(Request $request)
    {
        $status = $request->status;
        $customer_id = session('customer_id');

        $query = Pesanan::with(['customer', 'detailPesanans.menu'])
            ->where('customer_id', $customer_id);

        if ($status && $status != 'all') {
            $query->whereRaw('LOWER(payment_status) = ?', [strtolower($status)]);
        }

        $riwayat = $query->latest()->get();

        $html = view('frontend.riwayat._list', compact('riwayat'))->render();

        return response()->json([
            'html' => $html
        ]);
    }

    
}
