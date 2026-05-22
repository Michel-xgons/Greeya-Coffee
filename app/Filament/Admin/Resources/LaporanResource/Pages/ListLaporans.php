<?php

namespace App\Filament\Admin\Resources\LaporanResource\Pages;

use App\Filament\Admin\Resources\LaporanResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Widgets\StatistikPendapatan;
use App\Filament\Admin\Widgets\GrafikPenjualan;
use App\Filament\Admin\Widgets\MenuTerlaris;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;
    protected static ?string $title = 'Laporan Penjualan';

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatistikPendapatan::class,
            GrafikPenjualan::class,
            MenuTerlaris::class,
        ];
    }

    public function getTabs(): array
{
    return [

        'harian' => Tab::make('Harian')
            ->modifyQueryUsing(function (Builder $query) {

                $query->selectRaw('
                    DATE(created_at) as tanggal,
                    SUM(total_harga) as total,
                    COUNT(*) as jumlah_transaksi,
                    MIN(id) as id
                ')
                    ->where('payment_status', 'paid')
                    ->groupBy('tanggal')
                    ->orderByDesc('tanggal');
            }),

        'mingguan' => Tab::make('Mingguan')
            ->modifyQueryUsing(function (Builder $query) {

                $query->selectRaw('
                    YEARWEEK(created_at, 1) as minggu,
                    MIN(DATE(created_at)) as tanggal,
                    SUM(total_harga) as total,
                    COUNT(*) as jumlah_transaksi,
                    MIN(id) as id
                ')
                    ->where('payment_status', 'paid')
                    ->groupBy('minggu')
                    ->orderByDesc('minggu');
            }),

        'bulanan' => Tab::make('Bulanan')
            ->modifyQueryUsing(function (Builder $query) {

                $query->selectRaw('
                    DATE_FORMAT(created_at, "%Y-%m") as bulan,
                    MIN(DATE(created_at)) as tanggal,
                    SUM(total_harga) as total,
                    COUNT(*) as jumlah_transaksi,
                    MIN(id) as id
                ')
                    ->where('payment_status', 'paid')
                    ->groupBy('bulan')
                    ->orderByDesc('bulan');
            }),
    ];
}
}


