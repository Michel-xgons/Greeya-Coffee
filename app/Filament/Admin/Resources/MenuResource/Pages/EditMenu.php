<?php

namespace App\Filament\Admin\Resources\MenuResource\Pages;

use App\Filament\Admin\Resources\MenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = $data['stock'] > 0 ? 'tersedia' : 'habis';

        return $data;
    }
    
}
