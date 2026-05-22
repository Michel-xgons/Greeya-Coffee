<?php

namespace App\Filament\Admin\Resources\LaporanBulananResource\Pages;

use App\Filament\Admin\Resources\LaporanBulananResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanBulanan extends EditRecord
{
    protected static string $resource = LaporanBulananResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
