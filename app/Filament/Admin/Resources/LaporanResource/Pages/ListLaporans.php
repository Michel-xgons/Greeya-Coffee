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

    public function getDefaultActiveTab(): string
    {
        return request()->query('activeTab', 'harian');
    }

    protected function getHeaderActions(): array
{
    return [

        Action::make('print_harian')
            ->label('PDF Harian')
            ->icon('heroicon-o-printer')
            ->url(route('laporan.print', [
                'filter' => 'harian',
            ]))
            ->openUrlInNewTab(),

        Action::make('print_mingguan')
            ->label('PDF Mingguan')
            ->icon('heroicon-o-printer')
            ->url(route('laporan.print', [
                'filter' => 'mingguan',
            ]))
            ->openUrlInNewTab(),

        Action::make('print_bulanan')
            ->label('PDF Bulanan')
            ->icon('heroicon-o-printer')
            ->url(route('laporan.print', [
                'filter' => 'bulanan',
            ]))
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
                        now()->endOfWeek(),
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
