<?php

namespace App\Filament\Resources\PaymentMethods\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PaymentMethodForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->label('Name'),
                TextInput::make('code')
                    ->required()
                    ->label('Code')
                    ->helperText('Unique identifier, lowercase/underscored.'),
                TextInput::make('stripe_type')
                    ->label('Stripe payment method type')
                    ->placeholder('card, paypal, afterpay_clearpay, etc'),
                Select::make('fee_type')
                    ->options([
                        'flat' => 'Flat',
                        'percent' => 'Percent',
                    ])
                    ->required()
                    ->default('flat'),
                TextInput::make('fee_amount')
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->default(0)
                    ->label('Fee amount'),
                TextInput::make('badge')->label('Badge')->placeholder('Preferred / Wallet'),
                TextInput::make('description')->label('Description'),
                Toggle::make('is_active')->default(true)->label('Active'),
            ]);
    }
}
