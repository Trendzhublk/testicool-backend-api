<?php

namespace App\Filament\Resources\Countries\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CountryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('ISO Code')
                    ->required()
                    ->maxLength(2)
                    ->helperText('2-letter ISO code'),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
