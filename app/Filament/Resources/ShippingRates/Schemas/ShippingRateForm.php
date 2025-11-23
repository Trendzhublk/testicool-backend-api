<?php

namespace App\Filament\Resources\ShippingRates\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Illuminate\Support\Facades\DB;
use Filament\Schemas\Schema;

class ShippingRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('code')
                    ->required()
                    ->maxLength(100)
                    ->label('Code (slug)'),
                TextInput::make('label')
                    ->required()
                    ->maxLength(255)
                    ->label('Label shown to customer'),
                TextInput::make('carrier')
                    ->maxLength(255)
                    ->placeholder('DHL / FedEx / Local agent'),
                Select::make('shipping_agent_id')
                    ->label('Shipping Agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select agent (optional)'),
                Select::make('country_code')
                    ->label('Country (ISO-2)')
                    ->options(fn() => DB::table('countries')->orderBy('name')->pluck('name', 'code'))
                    ->searchable(),
                Select::make('rate_basis')
                    ->label('Rate type')
                    ->native(false)
                    ->options([
                        'country' => 'Country wise',
                        'weight' => 'Weight wise',
                        'quantity' => 'Quantity wise',
                    ])
                    ->default('country'),
                Select::make('charge_type')
                    ->label('Charge type')
                    ->native(false)
                    ->options([
                        'flat' => 'Flat rate',
                        'percent' => 'Percentage',
                    ])
                    ->default('flat'),
                TextInput::make('amount')
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Use currency rates repeater below.'),
                Repeater::make('rates')
                    ->label('Currency rates')
                    ->schema([
                        Select::make('currency')
                            ->options(fn() => DB::table('currencies')->orderBy('code')->pluck('code', 'code'))
                            ->required()
                            ->searchable(),
                        TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->step('0.01')
                            ->label('Amount'),
                        TextInput::make('tax_percent')
                            ->numeric()
                            ->minValue(0)
                            ->step('0.001')
                            ->label('Tax %')
                            ->default(0),
                    ])
                    ->addActionLabel('Add currency rate')
                    ->columns(3)
                    ->required()
                    ->statePath('currency_rates'),
                TextInput::make('weight_min')
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->label('Weight min (kg)')
                    ->visible(fn($get) => $get('rate_basis') === 'weight'),
                TextInput::make('weight_max')
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->label('Weight max (kg)')
                    ->visible(fn($get) => $get('rate_basis') === 'weight'),
                TextInput::make('qty_min')
                    ->numeric()
                    ->minValue(0)
                    ->label('Quantity min')
                    ->visible(fn($get) => $get('rate_basis') === 'quantity'),
                TextInput::make('qty_max')
                    ->numeric()
                    ->minValue(0)
                    ->label('Quantity max')
                    ->visible(fn($get) => $get('rate_basis') === 'quantity'),
                TextInput::make('estimated_days')
                    ->numeric()
                    ->minValue(0)
                    ->label('ETA (days)'),
                TextInput::make('priority')
                    ->numeric()
                    ->minValue(0)
                    ->default(100)
                    ->helperText('Lower number wins when multiple rates apply.'),
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
