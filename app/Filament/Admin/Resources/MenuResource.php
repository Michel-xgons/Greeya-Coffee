<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MenuResource\Pages;
use App\Models\Menus;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;

class MenuResource extends Resource
{
    protected static ?string $model = Menus::class;
    
    protected static ?string $navigationLabel = 'Tambah Menu';
    protected static ?string $pluralModelLabel = 'Menu';
    protected static ?string $modelLabel = ' Menu';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
        return [];
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
