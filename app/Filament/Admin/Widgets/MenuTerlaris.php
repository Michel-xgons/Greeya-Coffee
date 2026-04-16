<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;

class MenuTerlaris extends StatsOverviewWidget
{
    
    protected function getStats(): array
    {
        $menus = DetailPesanan::select(
                'menu_id',
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy('menu_id')
            ->orderByDesc('total')
            ->with('menu')
            ->limit(3)
            ->get();

        return $menus->map(function ($item, $index) {
            return Stat::make(
                ($index + 1) . '. ' . ($item->menu->nama_menu ?? 'Menu'),
                $item->total . ' x terjual'
            )
            ->color('warning');
        })->toArray();
    }
}