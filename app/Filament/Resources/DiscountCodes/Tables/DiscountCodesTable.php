<?php

namespace App\Filament\Resources\DiscountCodes\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiscountCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')->label('Code')->sortable()->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn($state) => ucfirst($state))
                    ->color(fn($state) => $state === 'percent' ? 'info' : 'primary'),
                TextColumn::make('value')
                    ->label('Value')
                    ->formatStateUsing(function ($state, $record) {
                        if ($record->type === 'percent') {
                            return number_format((float) $state, 2) . '%';
                        }

                        $currency = $record->currency ? strtoupper($record->currency) . ' ' : '';

                        return $currency . number_format((float) $state, 2);
                    })
                    ->sortable(),
                TextColumn::make('min_subtotal')->label('Min subtotal')->numeric(decimalPlaces: 2),
                TextColumn::make('region')->label('Region')->sortable(),
                TextColumn::make('usage_count')->label('Redeemed')->sortable(),
                TextColumn::make('starts_at')->label('Starts at')->dateTime()->since(),
                TextColumn::make('expires_at')->label('Expires at')->dateTime()->since(),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
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
