<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanResource\Pages;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;


class LaporanResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?string $pluralLabel = 'Laporan Penjualan';
    protected static ?string $label = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('No')
                    ->rowIndex(),

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('customer.no_telpon')
                    ->label('No Telp'),

                TextColumn::make('detailPesanans')
                    ->label('Menu')
                    ->formatStateUsing(function ($record) {

                        return $record->detailPesanans
                            ->map(function ($item) {

                                return $item->menu->nama_menu;
                            })->join(', ');
                    }),

                TextColumn::make('jumlah_menu')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($record) {

                        return $record->detailPesanans
                            ->map(function ($item) {

                                return $item->jumlah;
                            })->join(', ');
                    }),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR', true)
                    ->color('success')
                    ->weight('bold'),

                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->time('H:i'),
            ])

            ->defaultSort('created_at', 'desc');
    }



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }
}
