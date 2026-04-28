<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MejaResource\Pages;
use App\Models\Meja;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Forms\Form;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MejaResource extends Resource
{
    protected static ?string $model = Meja::class;

    protected static ?string $navigationLabel = 'Tambah Meja';
    protected static ?string $pluralModelLabel = 'Meja';
    protected static ?string $modelLabel = 'Tambah Meja';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_meja')
                    ->label('Nomor Meja')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live()
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if ($state) {
                            $set('qr_code', url('/pesan/meja/' . $state));
                        }
                    }),
                // ->afterStateUpdated(function ($state, callable $set) {
                //     $set('qr_code', url('/meja/' . $state));
                // }),

                TextInput::make('qr_code')
                    ->label('QR Code URL')
                    ->readOnly()
                    ->default(fn($record) => $record ? url('/pesan/meja/' . $record->nomor_meja) : null)
                    ->dehydrated(false),

                Select::make('status')
                    ->options([
                        'kosong' => 'Kosong',
                        'digunakan' => 'Digunakan',
                    ])
                    ->default('kosong')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nomor_meja')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('qr_code')
                    ->label('QR URL')
                    ->state(fn($record) => url('/pesan/meja/' . $record->nomor_meja))
                    ->url(fn($record) => url('/pesan/meja/' . $record->nomor_meja))
                    ->openUrlInNewTab()
                    ->limit(30),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state) => match ($state) {
                        'kosong' => 'success',
                        'digunakan' => 'danger',
                    }),
            ])
            ->actions([
                EditAction::make(),

                Action::make('qr')
                    ->label('QR')
                    ->icon('heroicon-o-qr-code')
                    ->modalHeading(fn($record) => 'QR Meja ' . $record->nomor_meja)
                    ->modalSubmitAction(false) // hilangkan tombol submit
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function ($record) {

                        $url = url('/pesan/meja/' . $record->nomor_meja);
                        $qr = QrCode::size(200)->generate($url);

                        return view('filament.qr-preview', [
                            'qr' => $qr,
                            'url' => $url,
                            'meja' => $record,
                        ]);
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMejas::route('/'),
            'create' => Pages\CreateMeja::route('/create'),
            'edit' => Pages\EditMeja::route('/{record}/edit'),
        ];
    }
}
