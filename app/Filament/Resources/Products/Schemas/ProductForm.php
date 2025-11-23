<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Info')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                    Textarea::make('description')
                        ->rows(5),
                ]),

                Section::make('Pricing & Status')->schema([
                    Grid::make(2)->schema([
                        TextInput::make('base_price')
                            ->numeric()
                            ->step('0.01')
                            ->required(),
                        Toggle::make('in_stock')->default(true),
                        Toggle::make('is_active')->default(true),
                        Toggle::make('is_featured')->default(false),
                    ]),
                ]),

                Section::make('Media & SEO')->schema([
                    FileUpload::make('cover_image')
                        ->label('Cover image')
                        ->image()
                        ->directory('admin-uploads/products')
                        ->preserveFilenames(),
                    Grid::make(2)->schema([
                        TextInput::make('meta_title')->maxLength(255),
                        Textarea::make('meta_description')->rows(2)->maxLength(255),
                    ]),
                ]),
            ]);
    }
}
