<?php

namespace App\Filament\Admin\Resources\LaporanResource\Pages;

use App\Filament\Admin\Resources\LaporanResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Widgets\StatistikPendapatan;
use App\Filament\Admin\Widgets\GrafikPenjualan;
use App\Filament\Admin\Widgets\MenuTerlaris;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\Action;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;
    protected static ?string $title = 'Laporan Penjualan';

    protected function getHeaderActions(): array
    {
        return [

            Action::make('print')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->url(function () {

                    $filter = request()->query('activeTab') ?? 'harian';

                    return route('laporan.print', [
                        'filter' => $filter
                    ]);
                })
                ->openUrlInNewTab(),

        ];
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

            'harian' => Tab::make('Hari Ini')
                ->modifyQueryUsing(function (Builder $query) {

                    $query->whereDate('created_at', today())
                        ->where('payment_status', 'paid');
                }),

            'mingguan' => Tab::make('Minggu Ini')
                ->modifyQueryUsing(function (Builder $query) {

                    $query->whereBetween('created_at', [
                        now()->startOfWeek(),
                        now()->endOfWeek()
                    ])
                        ->where('payment_status', 'paid');
                }),

            'bulanan' => Tab::make('Bulan Ini')
                ->modifyQueryUsing(function (Builder $query) {

                    $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where('payment_status', 'paid');
                }),
        ];
    }
}
