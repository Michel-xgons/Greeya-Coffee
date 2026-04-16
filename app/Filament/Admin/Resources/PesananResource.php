<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Admin\Resources\PesananResource\RelationManagers\DetailPesanansRelationManager;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                TextColumn::make('detailPesanans.menu.nama_menu')
                    ->label('Menu')
                    ->listWithLineBreaks(),
                TextColumn::make('detailPesanans.variant')->label('Varian'),
                TextColumn::make('detailPesanans.jumlah')->label('Jumlah Pesanan'),
                TextColumn::make('waktu_pesan')->label('Waktu pesanan'),
                TextColumn::make('note')->label('Catatan'),
                TextColumn::make('meja_id')->label('Nomor Meja'),
                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->badge()
                    ->colors([
                        'success' => 'PAID',
                        'danger' => 'PENDING',
                    ]),
                TextColumn::make('total_harga')
                    ->money('IDR'),
                TextColumn::make('customer.name')->label('Nama Customer'),
                TextColumn::make('customer.no_telpon')->label('Nomor HP'),
                TextColumn::make('customer.email')->label('Email'),
                


                // TextColumn::make('waktu_pesan')
                //     ->label('Waktu Pesanan')
                //     ->since(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            DetailPesanansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanans::route('/'),

        ];
    }
}
