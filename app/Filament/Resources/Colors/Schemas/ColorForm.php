<?php

namespace App\Filament\Resources\Colors\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ColorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                ColorPicker::make('hex')
                    ->label('Hex')
                    ->formatStateUsing(fn($state) => $state ?: '#000000')
                    ->required(),
                Toggle::make('is_active')
                    ->default(true),
            ]);
    }
}
