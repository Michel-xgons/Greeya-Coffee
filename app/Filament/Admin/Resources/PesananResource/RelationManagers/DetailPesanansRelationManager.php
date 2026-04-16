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
                TextColumn::make('menu.nama_menu')->label('Menu'),

                TextColumn::make('variant')
                    ->formatStateUsing(fn ($state) => strtoupper($state ?? '-')),

                TextColumn::make('jumlah')->label('Qty'),

                TextColumn::make('note')
                    ->label('Catatan')
                    ->wrap(),
            ]);
    }
}