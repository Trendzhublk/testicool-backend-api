<?php

namespace App\Filament\Resources\DiscountCodes\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DiscountCodeForm
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
                TextInput::make('code')
                    ->required()
                    ->label('Code')
                    ->maxLength(50)
                    ->helperText('Exact code customers will enter.')
                    ->extraInputAttributes(['style' => 'text-transform:uppercase']),
                TextInput::make('description')
                    ->label('Description')
                    ->maxLength(255)
                    ->columnSpan(1),
                Select::make('type')
                    ->required()
                    ->native(false)
                    ->options([
                        'amount' => 'Fixed amount',
                        'percent' => 'Percent off',
                    ]),
                TextInput::make('value')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->label('Discount value'),
                TextInput::make('currency')
                    ->maxLength(3)
                    ->label('Currency (for amount codes)')
                    ->helperText('Leave empty for percent codes. ISO code e.g. GBP.'),
                TextInput::make('min_subtotal')
                    ->numeric()
                    ->minValue(0)
                    ->step('0.01')
                    ->label('Minimum subtotal'),
                Select::make('region')
                    ->native(false)
                    ->options($regions)
                    ->placeholder('Any region')
                    ->label('Region lock'),
                TextInput::make('max_redemptions')
                    ->numeric()
                    ->minValue(0)
                    ->label('Max redemptions (global)'),
                TextInput::make('max_redemptions_per_user')
                    ->numeric()
                    ->minValue(0)
                    ->label('Max per email/user'),
                Toggle::make('once_per_email')
                    ->label('One-time per email')
                    ->inline(false),
                TagsInput::make('allowed_emails')
                    ->placeholder('name@example.com')
                    ->label('Allowed emails (optional)')
                    ->helperText('Leave empty to allow anyone.'),
                DateTimePicker::make('starts_at')
                    ->label('Starts at')
                    ->withoutSeconds(),
                DateTimePicker::make('expires_at')
                    ->label('Expires at')
                    ->withoutSeconds(),
                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active')
                    ->columnSpanFull(),
            ]);
    }
}
