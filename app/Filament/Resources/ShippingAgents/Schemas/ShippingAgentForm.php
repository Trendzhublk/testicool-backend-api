<?php

namespace App\Filament\Resources\ShippingAgents\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ShippingAgentForm
{
    public static function configure(Schema $schema): Schema
    {
        $regions = [
            'uk' => 'UK',
            'eu' => 'Europe',
            'us' => 'USA',
            'dubai' => 'Dubai / GCC',
            'au' => 'Australia / NZ',
        ];

        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('email')->email()->maxLength(255),
                TextInput::make('phone')->maxLength(50),
                Select::make('region')->native(false)->options($regions)->placeholder('Any region'),
                TextInput::make('country_code')
                    ->maxLength(2)
                    ->label('Country code (ISO-2)')
                    ->placeholder('GB'),
                TextInput::make('priority')
                    ->numeric()
                    ->minValue(0)
                    ->default(100),
                Toggle::make('is_active')->default(true)->label('Active'),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }
}
