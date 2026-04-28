<?php

namespace App\Filament\Admin\Resources\LaporanResource\Pages;

use Filament\Resources\Pages\Page;

use App\Models\Pesanan;
use App\Filament\Admin\Resources\LaporanResource;


class LaporanHarian extends Page
{
    protected static string $resource = LaporanResource::class;

    protected static string $view = 'filament.admin.resources.laporan-resource.pages.laporan-harian';

    public $tanggal;
    public $data;
    public $total;

    public function mount()
    {
        $this->tanggal = request()->route('tanggal');

        $this->data = Pesanan::with([
    'customer:id,name,no_telpon,email',
    'detailPesanans:id,pesanan_id,menu_id,jumlah',
    'detailPesanans.menu:id,nama_menu'
])
            ->whereDate('created_at', $this->tanggal)
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $this->total = $this->data->sum('total_harga');
    }

    protected function getViewData(): array
{
    return [
        'tanggal' => $this->tanggal,
        'data' => $this->data,
        'total' => $this->total,
    ];
}
}
