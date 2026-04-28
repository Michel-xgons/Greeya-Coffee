<?php

namespace App\Filament\Admin\Resources\PesananResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Pesanan;
use Filament\Notifications\Notification;

class NotifPesananMasuk extends Widget
{
    protected static string $view = 'filament.admin.resources.pesanan-resource.widgets.notif-pesanan-masuk';
    protected static ?string $pollingInterval = '5s';
    protected int | string | array $columnSpan = 'full';

    public $lastId = 0;
    public $count = 0;


    public function poll()
{
    $latest = Pesanan::latest()->first();

    if ($latest && $latest->id > $this->lastId) {
        Notification::make()
            ->title('Pesanan Baru!')
            ->body('Pesanan #' . $latest->id . ' masuk')
            ->success()
            ->send();

        $this->dispatch('play-sound');

        $this->lastId = $latest->id;
    }

    $this->count = Pesanan::count();
}

    public function mount()
    {
        $last = Pesanan::latest()->first();

        $this->lastId = $last?->id ?? 0;
        $this->count = Pesanan::count();
    }

    
}