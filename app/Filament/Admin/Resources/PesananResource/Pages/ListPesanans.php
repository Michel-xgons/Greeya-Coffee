<?php

namespace App\Filament\Admin\Resources\PesananResource\Pages;

use App\Filament\Admin\Resources\PesananResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Admin\Resources\PesananResource\Widgets\NotifPesananMasuk;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;
    protected static ?string $pollingInterval = '5s';
    protected function getHeaderWidgets(): array
    {
        return [
            NotifPesananMasuk::class,
        ];
    }
}
