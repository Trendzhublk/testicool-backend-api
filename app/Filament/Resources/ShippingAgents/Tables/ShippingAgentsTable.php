<?php

namespace App\Filament\Resources\ShippingAgents\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShippingAgentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Agent')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->sortable(),
                TextColumn::make('phone')->label('Phone'),
                TextColumn::make('region')->label('Region')->sortable(),
                TextColumn::make('country_code')->label('Country')->sortable(),
                TextColumn::make('priority')->sortable(),
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
