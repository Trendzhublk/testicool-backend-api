<?php

namespace App\Filament\Resources\Currencies\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('ISO Code')
                    ->required()
                    ->maxLength(3),
                TextInput::make('symbol')
                    ->maxLength(5),
                TextInput::make('rate_to_base')
                    ->numeric()
                    ->default(1)
                    ->step('0.000001')
                    ->label('Rate to Base'),
                Toggle::make('is_default')->label('Default currency'),
            ]);
    }
}
