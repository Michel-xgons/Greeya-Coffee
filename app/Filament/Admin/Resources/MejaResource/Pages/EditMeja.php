<?php

namespace App\Filament\Admin\Resources\MejaResource\Pages;

use App\Filament\Admin\Resources\MejaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMeja extends EditRecord
{
    protected static string $resource = MejaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
