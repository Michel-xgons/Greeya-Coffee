<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanResource\Pages;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Actions\Action;

class LaporanResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?string $pluralLabel = 'Laporan Penjualan';
    protected static ?string $label = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Penjualan';

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'customer',
                'detailPesanans.menu',
            ]);
    }
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

                TextColumn::make('detailPesanans.jumlah')
                    ->label('Jumlah')
                    ->formatStateUsing(function ($state) {

                        if (is_array($state)) {
                            return implode(', ', $state);
                        }

                        return $state;
                    }),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR', true)
                    ->color('success')
                    ->weight('bold')
                    ->summarize(
                        Sum::make()
                            ->money('IDR', true)
                            ->label('Total Pendapatan')
                    ),


                TextColumn::make('created_at')
                    ->label('Waktu')
                    ->time('H:i'),
            ])
            ->headerActions([
                Action::make('print')
                    ->label('Cetak PDF')
                    ->icon('heroicon-o-printer')
                    ->url(fn() => route('laporan.print'))
                    ->openUrlInNewTab(),
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
