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
use Illuminate\Database\Eloquent\Builder;

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
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable(),

                TextColumn::make('customer.no_telpon')
                    ->label('No HP'),

                TextColumn::make('payment_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'PAID',
                        'danger' => 'PENDING',
                    ]),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),

            ])
            ->recordUrl(fn($record) => route('filament.admin.resources.pesanans.view', $record))
            ->defaultSort('created_at', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'customer:id,name,no_telpon',
                'detailPesanans.menu:id,nama_menu,harga'
            ])
            ->withCount('detailPesanans');
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
            'view' => Pages\ViewPesanan::route('/{record}'),
        ];
    }
}
