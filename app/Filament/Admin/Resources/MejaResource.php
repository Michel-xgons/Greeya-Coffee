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

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nomor_meja')
                    ->numeric()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live()
                    ->afterStateUpdated(function (?string $state, callable $set) {
                        if ($state) {
                            $set('qr_code', url('/pesan/meja/' . $state));
                        }
                    }),

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
                    ->copyable()
                    ->url(fn($record) => url('/pesan/meja/' . $record->nomor_meja))
                    ->openUrlInNewTab()
                    ->limit(30),

                TextColumn::make('status')
                    ->label('Status Meja')
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
                    ->modalSubmitAction(false) 
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

                Action::make('toggleStatus')
                    ->label(
                        fn($record) =>
                        $record->status === 'kosong'
                            ? 'Digunakan'
                            : 'Kosong'
                    )

                    ->icon(
                        fn($record) =>
                        $record->status === 'kosong'
                            ? 'heroicon-m-x-circle'
                            : 'heroicon-m-check-circle'
                    )

                    ->color(
                        fn($record) =>
                        $record->status === 'kosong'
                            ? 'danger'
                            : 'success'
                    )

                    ->requiresConfirmation()

                    ->action(function ($record) {

                        $record->update([
                            'status' =>
                            $record->status === 'kosong'
                                ? 'digunakan'
                                : 'kosong'
                        ]);
                    }),
            ])
            ->actionsColumnLabel('Aksi')
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
