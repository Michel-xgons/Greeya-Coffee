<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuResource\Pages;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuResource extends Resource
{
    protected static ?string $model = \App\Models\Menu::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kategori_id')
                    ->relationship('kategori', 'nama_kategori')
                    ->required(),

                TextInput::make('nama_menu')
                    ->required()
                    ->maxLength(255),

                Textarea::make('deskripsi')
                    ->rows(3),

                TextInput::make('harga')
                    ->numeric()
                    ->required(),

                FileUpload::make('gambar')
                    ->image()
                    ->disk('public')
                    ->directory('menu-images')
                    ->visibility('public')
                    ->imagePreviewHeight('150')
                    ->nullable(),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori')
                    ->searchable(),

                ImageColumn::make('gambar')
                    ->square(),

                TextColumn::make('nama_menu')
                    ->searchable(),

                TextColumn::make('harga')
                    ->money('IDR', true),

                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
