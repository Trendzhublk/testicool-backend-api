<?php

namespace App\Filament\Resources\ProductImages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductImageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Image Details')->schema([
                    Grid::make(2)->schema([
                        Select::make('product_id')
                            ->relationship('product', 'title')
                            ->required(),
                        Select::make('variant_id')
                            ->relationship('variant', 'sku')
                            ->label('Variant')
                            ->searchable()
                            ->preload(),
                    ]),
                    FileUpload::make('path')
                        ->label('Image')
                        ->image()
                        ->directory('admin-uploads/products')
                        ->preserveFilenames()
                        ->required(),
                    Grid::make(2)->schema([
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(1),
                        TextInput::make('alt_text')
                            ->label('Alt text')
                            ->maxLength(255),
                    ]),
                ]),
            ]);
    }
}
