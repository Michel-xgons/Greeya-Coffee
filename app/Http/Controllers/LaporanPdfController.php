<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class LaporanPdfController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter ?? 'harian';

        $query = Pesanan::with([
            'customer',
            'detailPesanans.menu'
        ])->where('payment_status', 'paid');

        if ($filter === 'harian') {
            $query->whereDate('created_at', today());
        } elseif ($filter === 'mingguan') {
            $query->whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]);
        } elseif ($filter === 'bulanan') {
            $query->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year);
        }
        $data = $query->latest()->get();

        $total = $data->sum('total_harga');

        $pdf = Pdf::loadView('laporan', [
            'data' => $data,
            'total' => $total,
            'filter' => $filter
        ]);

        return $pdf->stream('laporan-penjualan.pdf');
    }
}
