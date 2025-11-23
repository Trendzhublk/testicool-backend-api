<?php

namespace App\Filament\Resources\Addresses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AddressesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_no')->label('Order #')->searchable(),
                TextColumn::make('customer_name')->label('Customer')->searchable(),
                TextColumn::make('country.name')->label('Country')->sortable(),
                BadgeColumn::make('status')->colors([
                    'warning' => 'pending',
                    'success' => 'delivered',
                    'info' => 'processing',
                    'danger' => 'cancelled',
                ]),
                BadgeColumn::make('payment_status')->colors([
                    'danger' => 'unpaid',
                    'success' => 'paid',
                    'info' => 'partial',
                ]),
                TextColumn::make('grand_total')->label('Total')->numeric(decimalPlaces: 2),
                TextColumn::make('currency_code')->label('Currency'),
                TextColumn::make('created_at')->dateTime()->since(),
            ])
            ->filters([
                //
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
