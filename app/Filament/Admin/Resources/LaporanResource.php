<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanResource\Pages;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\Summarizers\Sum;


class LaporanResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?string $pluralLabel = 'Laporan Penjualan';
    protected static ?string $label = 'Laporan';
    protected static ?string $navigationLabel = 'Laporan Penjualan';

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
                TextColumn::make('kode_pesanan')
                    ->label('Kode Pesanan')
                    ->searchable(),
                    

                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->default('-'),

                TextColumn::make('meja_id')
                    ->label('Meja')
                    ->formatStateUsing(fn($state) => 'Meja ' . $state),

                TextColumn::make('detailPesanans.menu.nama_menu')
                    ->label('Menu')
                    ->listWithLineBreaks(),

                TextColumn::make('total_harga')
                    ->label('Total')
                    ->money('IDR', true)
                    ->summarize(
                        Sum::make()
                            ->label('Total Pendapatan')
                            ->money('IDR', true)
                    ),

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
            ->defaultSort('created_at', 'desc')

            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status')
                    ->options([
                        'PAID' => 'Paid',
                        'PENDING' => 'Pending',
                    ])
                    ->default('PAID'),

                SelectFilter::make('meja_id')
                    ->label('Meja')
                    ->options([
                        1 => 'Meja 1',
                        2 => 'Meja 2',
                        3 => 'Meja 3',
                        4 => 'Meja 4',
                        5 => 'Meja 5',
                    ]),

                Filter::make('tanggal')
                    ->form([
                        DatePicker::make('from')->label('Dari'),
                        DatePicker::make('until')->label('Sampai'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(
                                $data['until'],
                                fn($q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    }),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'detailPesanans.menu:id,nama_menu',
                'customer:id,name'
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
        ];
    }
}
