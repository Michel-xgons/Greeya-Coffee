<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        $no_hp = session('phone');

        $riwayat = Pesanan::with(['customer','detailPesanans.menu'])
            ->whereHas('customer', function ($query) use ($no_hp) {
                $query->where('no_telpon', $no_hp);
            })
            ->whereIn('payment_status', ['unpaid','pending'])
            ->latest()
            ->get();

        return view('frontend.Menu.RiwayatPesanan', compact('riwayat'));
    }
}