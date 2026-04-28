<?php

namespace App\Filament\Admin\Resources\PesananResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DetailPesanansRelationManager extends RelationManager
{
    protected static string $relationship = 'detailPesanans';

    protected static ?string $title = 'Detail Pesanan';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('menu.nama_menu')
                    ->label('Menu'),

                TextColumn::make('variant')
                    ->label('Variant'),

                TextColumn::make('jumlah')
                    ->label('Jumlah'),

                TextColumn::make('menu.harga')
                    ->label('Harga')
                    ->money('IDR', true),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->formatStateUsing(fn($state) => $state && $state !== 'EMPTY' ? $state : '-')
                    ->wrap(),

                TextColumn::make('subtotal')
                    ->label('Subtotal')
                    ->getStateUsing(fn($record) => ($record->jumlah ?? 0) * ($record->menu->harga ?? 0))
                    ->money('IDR', true),

            ])
            ->striped()
            ->defaultSort('id', 'asc');
    }
}
