<?php

namespace App\Filament\Resources\ShippingRates;

use App\Filament\Resources\ShippingRates\Pages\CreateShippingRate;
use App\Filament\Resources\ShippingRates\Pages\EditShippingRate;
use App\Filament\Resources\ShippingRates\Pages\ListShippingRates;
use App\Filament\Resources\ShippingRates\Schemas\ShippingRateForm;
use App\Filament\Resources\ShippingRates\Tables\ShippingRatesTable;
use App\Models\ShippingRate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ShippingRateResource extends Resource
{
    protected static ?string $model = ShippingRate::class;

    protected static UnitEnum|string|null $navigationGroup = 'Orders';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedTruck;

    public static function form(Schema $schema): Schema
    {
        return ShippingRateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingRatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
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

    public static function getPages(): array
    {
        return [
            'index' => ListShippingRates::route('/'),
            'create' => CreateShippingRate::route('/create'),
            'edit' => EditShippingRate::route('/{record}/edit'),
        ];
    }
}
