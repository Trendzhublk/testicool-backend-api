<?php

namespace App\Filament\Resources\Addresses;

use App\Filament\Resources\Addresses\Pages\CreateAddress;
use App\Filament\Resources\Addresses\Pages\EditAddress;
use App\Filament\Resources\Addresses\Pages\ListAddresses;
use App\Filament\Resources\Addresses\Schemas\AddressForm;
use App\Filament\Resources\Addresses\Tables\AddressesTable;
use App\Models\Address;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AddressResource extends Resource
{
    protected static ?string $model = Address::class;

    protected static UnitEnum|string|null $navigationGroup = 'Sales';
    protected static ?string $navigationLabel = 'Orders';


    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AddressForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AddressesTable::configure($table);
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
            'index' => ListAddresses::route('/'),
            'create' => CreateAddress::route('/create'),
            'edit' => EditAddress::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return ($user?->isAdmin() || $user?->isSalesAgent()) ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->isSalesAgent() && ! $user->isAdmin()) {
            $query->where('country_code', $user->country_code);
        }

        return $query;
    }

    public static function canViewAny(): bool
    {
        $user = auth()->user();

        return ($user?->isAdmin() || $user?->isSalesAgent()) ?? false;
    }

    public static function canView(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSalesAgent() && $record->country_code === $user->country_code;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        return $user->isSalesAgent() && $record->country_code === $user->country_code;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
