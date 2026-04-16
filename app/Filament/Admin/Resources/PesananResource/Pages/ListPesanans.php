<?php

namespace App\Filament\Admin\Resources\PesananResource\Pages;

use App\Filament\Admin\Resources\PesananResource;

use App\Models\Pesanan;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListPesanans extends ListRecords
{
    protected static string $resource = PesananResource::class;
    protected static ?string $pollingInterval = '5s';
    
    protected function getHeaderActions(): array
    {
        return [];
    }

    protected int $lastCount = 0;

public function mount(): void
{
    parent::mount();

    $this->lastCount = Pesanan::where('payment_status', 'PENDING')->count();
}

public function hydrate(): void
{
    $current = Pesanan::where('payment_status', 'PENDING')->count();

    if ($current > $this->lastCount) {
        Notification::make()
            ->title('Pesanan Baru!')
            ->body('Ada pesanan masuk')
            ->success()
            ->send();

        $this->dispatchBrowserEvent('play-sound');
    }

    $this->lastCount = $current;
}
}
