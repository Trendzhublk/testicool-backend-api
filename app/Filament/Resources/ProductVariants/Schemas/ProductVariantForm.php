<?php

namespace App\Filament\Resources\ProductVariants\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductVariantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship('product', 'title')
                    ->required(),
                Select::make('color_id')
                    ->relationship('color', 'name')
                    ->required(),
                Select::make('size_id')
                    ->relationship('size', 'name')
                    ->required(),
                TextInput::make('sku')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('price_override')
                    ->numeric()
                    ->step('0.01')
                    ->label('Price')
                    ->required(),
                TextInput::make('stock_qty')
                    ->numeric()
                    ->default(0)
                    ->label('Stock'),
                TextInput::make('weight')
                    ->numeric()
                    ->step('0.001')
                    ->label('Weight (kg)'),
                Toggle::make('is_active')->default(true),
            ]);
    }
}
