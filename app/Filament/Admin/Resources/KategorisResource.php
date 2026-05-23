<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KategorisResource\Pages;
use App\Models\Kategoris;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;

class KategorisResource extends Resource
{
    protected static ?string $model = Kategoris::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationLabel = 'Kategori Menu';

    protected static ?string $pluralLabel = 'Kategori Menu';

    protected static ?string $label = 'Kategori';

    protected static ?string $navigationGroup = 'Manajemen Menu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->required()
                    ->maxLength(255),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nama_kategori')
                    ->label('Nama Kategori')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y'),

            ])
            ->filters([
                //
            ])
            ->actions([

                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([

                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum ada kategori');
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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategoris::route('/create'),
            'edit' => Pages\EditKategoris::route('/{record}/edit'),
        ];
    }
}