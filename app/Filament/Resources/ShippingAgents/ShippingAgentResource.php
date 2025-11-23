<?php

namespace App\Filament\Resources\ShippingAgents;

use App\Filament\Resources\ShippingAgents\Pages\CreateShippingAgent;
use App\Filament\Resources\ShippingAgents\Pages\EditShippingAgent;
use App\Filament\Resources\ShippingAgents\Pages\ListShippingAgents;
use App\Filament\Resources\ShippingAgents\Schemas\ShippingAgentForm;
use App\Filament\Resources\ShippingAgents\Tables\ShippingAgentsTable;
use App\Models\ShippingAgent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ShippingAgentResource extends Resource
{
    protected static ?string $model = ShippingAgent::class;

    protected static UnitEnum|string|null $navigationGroup = 'Orders';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return ShippingAgentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShippingAgentsTable::configure($table);
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
            'index' => ListShippingAgents::route('/'),
            'create' => CreateShippingAgent::route('/create'),
            'edit' => EditShippingAgent::route('/{record}/edit'),
        ];
    }
}
