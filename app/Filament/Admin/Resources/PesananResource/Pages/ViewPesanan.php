<?php

namespace App\Filament\Admin\Resources\PesananResource\Pages;

use App\Filament\Admin\Resources\PesananResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;

class ViewPesanan extends ViewRecord
{
    protected static string $resource = PesananResource::class;

    public function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([

            Section::make('Informasi Pesanan')
                ->schema([
                    TextEntry::make('kode_pesanan')->label('Kode'),

                    TextEntry::make('meja_id')
                        ->label('Meja')
                        ->formatStateUsing(fn ($state) => 'Meja ' . $state),

                    TextEntry::make('customer.name')->label('Customer'),

                    TextEntry::make('customer.no_telpon')->label('No HP'),

                    TextEntry::make('created_at')
                        ->label('Tanggal')
                        ->dateTime('d M Y H:i'),
                ])
                ->columns(2),

            Section::make('Total')
                ->schema([
                    TextEntry::make('total_harga')
                        ->label('Total Bayar')
                        ->money('IDR', true)
                        ->weight('bold'),

                    TextEntry::make('payment_status')
                        ->label('Status')
                        ->badge()
                        ->color(fn ($state) => strtolower($state) === 'paid' ? 'success' : 'danger')
                ]),
        ]);
}
}


