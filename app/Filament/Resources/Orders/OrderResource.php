<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Models\Order;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static UnitEnum|string|null $navigationGroup = 'Orders';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('tracking_number')
                    ->label('Tracking #')
                    ->disabled()
                    ->columnSpan(1),
                Select::make('status')
                    ->required()
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ])
                    ->native(false),
                Select::make('shipping_agent_id')
                    ->relationship('shippingAgent', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Shipping agent'),
                Select::make('sales_agent_id')
                    ->relationship('salesAgent', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Sales agent'),
                Textarea::make('status_note')
                    ->label('Status note')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_number')
                    ->label('Tracking #')
                    ->copyable()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('customer_email')
                    ->label('Email')
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'pending',
                        'info' => 'processing',
                        'warning' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ])
                    ->sortable(),
                TextColumn::make('shippingAgent.name')
                    ->label('Shipping agent')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('salesAgent.name')
                    ->label('Sales agent')
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('status_updated_at')
                    ->label('Updated')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('shipping_agent_id')
                    ->label('Shipping agent')
                    ->relationship('shippingAgent', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('sales_agent_id')
                    ->label('Sales agent')
                    ->relationship('salesAgent', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('status_updated_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return ($user?->isAdmin() || $user?->isSalesAgent()) ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
