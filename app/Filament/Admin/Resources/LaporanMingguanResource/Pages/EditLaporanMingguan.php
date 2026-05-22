<?php

namespace App\Filament\Admin\Resources\LaporanMingguanResource\Pages;

use App\Filament\Admin\Resources\LaporanMingguanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanMingguan extends EditRecord
{
    protected static string $resource = LaporanMingguanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
