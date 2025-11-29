<?php

namespace App\Filament\Resources\Addresses\AddressResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components($this->getFormComponents());
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('tracking_number')->label('Tracking #')->copyable()->sortable(),
                TextColumn::make('title_snapshot')->label('Product')->wrap()->sortable(),
                TextColumn::make('sku_snapshot')->label('SKU')->sortable(),
                TextColumn::make('size_snapshot')->label('Size')->sortable(),
                TextColumn::make('color_snapshot')->label('Color')->sortable(),
                TextColumn::make('qty')->label('Qty')->sortable(),
                TextColumn::make('price_snapshot')->label('Unit price')->numeric(decimalPlaces: 2),
                TextColumn::make('line_total')->label('Line total')->numeric(decimalPlaces: 2),
                TextColumn::make('status')->label('Status')->sortable(),
            ])
            ->headerActions([])
            ->actions([
                EditAction::make()
                    ->label('View')
                    ->schema($this->getFormComponents())
                    ->modalHeading('Order Item'),
            ])
            ->bulkActions([]);
    }

    protected function getFormComponents(): array
    {
        return [
            TextInput::make('title_snapshot')->label('Product')->disabled(),
            TextInput::make('sku_snapshot')->label('SKU')->disabled(),
            TextInput::make('size_snapshot')->label('Size')->disabled(),
            TextInput::make('color_snapshot')->label('Color')->disabled(),
            TextInput::make('qty')->label('Qty')->numeric()->disabled(),
            TextInput::make('price_snapshot')->label('Unit price')->numeric()->disabled(),
            TextInput::make('line_total')->label('Line total')->numeric()->disabled(),
            TextInput::make('tracking_number')->label('Tracking #')->disabled(),
            Textarea::make('status_note')->label('Status note')->disabled(),
        ];
    }
}
