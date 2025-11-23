<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Info')
                    ->schema([
                        TextInput::make('order_no')->required()->maxLength(255),
                        Select::make('currency_code')
                            ->relationship('currency', 'code')
                            ->label('Currency')
                            ->required(),
                        Select::make('country_code')
                            ->relationship('country', 'name')
                            ->label('Country')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                                'refunded' => 'Refunded',
                            ])
                            ->required(),
                        Select::make('payment_status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'paid' => 'Paid',
                                'refunded' => 'Refunded',
                                'partial' => 'Partial',
                            ])
                            ->required(),
                        Grid::make(2)->schema([
                            TextInput::make('subtotal')->numeric()->step('0.01'),
                            TextInput::make('discount_total')->numeric()->step('0.01'),
                            TextInput::make('shipping_total')->numeric()->step('0.01'),
                            TextInput::make('tax_total')->numeric()->step('0.01'),
                            TextInput::make('grand_total')->numeric()->step('0.01'),
                        ]),
                    ]),
                Section::make('Customer')
                    ->schema([
                        TextInput::make('customer_name')->required()->maxLength(255),
                        TextInput::make('customer_email')->email()->required(),
                        TextInput::make('customer_phone')->maxLength(255),
                    ]),
                Section::make('Addresses')
                    ->schema([
                        KeyValue::make('shipping_address')->label('Shipping address'),
                        KeyValue::make('billing_address')->label('Billing address'),
                    ]),
                Section::make('Notes')
                    ->schema([
                        Textarea::make('notes')->rows(3),
                    ]),
            ]);
    }
}
