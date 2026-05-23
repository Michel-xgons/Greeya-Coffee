<?php

namespace App\Filament\Admin\Resources\KategorisResource\Pages;

use App\Filament\Admin\Resources\KategorisResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoris extends EditRecord
{
    protected static string $resource = KategorisResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
