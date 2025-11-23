<?php

namespace App\Filament\Resources\ProductImages;

use App\Filament\Resources\ProductImages\Pages\CreateProductImage;
use App\Filament\Resources\ProductImages\Pages\EditProductImage;
use App\Filament\Resources\ProductImages\Pages\ListProductImages;
use App\Filament\Resources\ProductImages\Schemas\ProductImageForm;
use App\Filament\Resources\ProductImages\Tables\ProductImagesTable;
use App\Models\ProductImage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProductImageResource extends Resource
{
    protected static ?string $model = ProductImage::class;

    protected static UnitEnum|string|null $navigationGroup = 'Catalog';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ProductImageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductImagesTable::configure($table);
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
            'index' => ListProductImages::route('/'),
            'create' => CreateProductImage::route('/create'),
            'edit' => EditProductImage::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return $user?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
