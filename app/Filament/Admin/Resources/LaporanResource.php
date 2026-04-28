<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanResource\Pages;
use App\Models\Pesanan;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;


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
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y'),

                TextColumn::make('total')
                    ->label('Total Pendapatan')
                    ->money('IDR', true)
                    ->weight('bold')
                    ->color('success'),
            ])
            ->recordUrl(
    fn ($record) => route(
        'filament.admin.resources.laporans.laporan-harian',
        ['tanggal' => $record->tanggal]
    )
)
            ->defaultSort('tanggal', 'desc');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->selectRaw('
            DATE(created_at) as tanggal,
            SUM(total_harga) as total,
            MIN(id) as id
        ')
            ->where('payment_status', 'paid')
            ->groupBy('tanggal')
            ->orderByDesc('tanggal');
    }

    public static function getPages(): array
{
    return [
        'index' => Pages\ListLaporans::route('/'),
        'laporan-harian' => Pages\LaporanHarian::route('/harian/{tanggal}'),
    ];
}
}
