<?php

namespace App\Filament\Admin\Resources\LaporanMingguanResource\Pages;

use App\Filament\Admin\Resources\LaporanMingguanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanMingguans extends ListRecords
{
    protected static string $resource = LaporanMingguanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
