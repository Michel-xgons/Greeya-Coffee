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
                            $set('qr_code', config('app.url') . '/meja/' . $state);
                        }
                    }),
                // ->afterStateUpdated(function ($state, callable $set) {
                //     $set('qr_code', url('/meja/' . $state));
                // }),

                TextInput::make('qr_code')
                    ->label('QR Code URL')
                    ->readOnly()
                    ->dehydrated(),

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
