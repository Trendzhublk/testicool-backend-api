<?php

namespace App\Filament\Resources\ShippingRates\Tables;

use App\Models\Country;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Tables;

class ShippingRatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label')->label('Label')->sortable()->searchable(),
                TextColumn::make('code')->label('Code')->sortable()->searchable(),
                TextColumn::make('carrier')->label('Carrier')->sortable()->searchable(),
                TextColumn::make('agent.name')->label('Agent')->sortable()->searchable(),
                TextColumn::make('country_code')->label('Country')->badge()->sortable()->searchable(),
                TextColumn::make('rate_basis')->label('Rate type')->badge(),
                TextColumn::make('charge_type')->label('Charge')->badge(),
                TextColumn::make('tax_percent')->label('Tax %')->numeric(decimalPlaces: 3),
                TextColumn::make('amount')
                    ->label('Amount (GBP)')
                    ->numeric(decimalPlaces: 2),
                TextColumn::make('priority')->sortable(),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->filters([
                SelectFilter::make('country_code')
                    ->label('Country')
                    ->options(fn() => Country::query()->orderBy('code')->pluck('code', 'code')->all()),
                SelectFilter::make('shipping_agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('priority')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
