<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatistikPendapatan extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Pendapatan Hari Ini',
                'Rp ' . number_format(
                    Pesanan::whereDate('created_at', today())
                        ->where('payment_status', 'PAID')
                        ->sum('total_harga'),
                    0, ',', '.'
                )
            )
            ->description('Total transaksi berhasil hari ini')
            ->color('success'),
        ];
    }
}