<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Pesanan;
use Illuminate\Support\Collection;

class GrafikPenjualan extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan Bulanan';

    protected function getData(): array
    {
        $rawData = $this->getMonthlySales();
        $months = $this->getMonthLabels();

        // Inisialisasi semua bulan = 0
        $formatted = collect(range(1, 12))->mapWithKeys(fn ($month) => [
            $month => 0
        ]);

        // Isi data sesuai hasil query
        foreach ($rawData as $item) {
            $formatted[$item->bulan] = (int) $item->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pendapatan Bulanan',
                    'data' => $formatted->values(),
                ],
            ],
            'labels' => array_values($months),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    /**
     * Ambil data penjualan per bulan
     */
    private function getMonthlySales(): Collection
    {
        return Pesanan::query()
            ->selectRaw('MONTH(created_at) as bulan, SUM(total_harga) as total')
            ->where('payment_status', 'PAID')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();
    }

    /**
     * Label bulan
     */
    private function getMonthLabels(): array
    {
        return [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];
    }
}