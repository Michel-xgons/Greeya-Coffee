<?php

namespace App\Filament\Admin\Resources\LaporanResource\Pages;

use App\Filament\Admin\Resources\LaporanResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Widgets\StatistikPendapatan;
use App\Filament\Admin\Widgets\GrafikPenjualan;
use App\Filament\Admin\Widgets\MenuTerlaris;

class ListLaporans extends ListRecords
{
    protected static string $resource = LaporanResource::class;

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
}
