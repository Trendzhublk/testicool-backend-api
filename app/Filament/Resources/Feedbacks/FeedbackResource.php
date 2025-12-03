<?php

namespace App\Filament\Resources\Feedbacks;

use App\Filament\Resources\Feedbacks\Pages\EditFeedback;
use App\Filament\Resources\Feedbacks\Pages\ListFeedback;
use App\Models\Feedback;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;

    protected static UnitEnum|string|null $navigationGroup = 'Support';

    protected static BackedEnum|string|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->native(false),
                Textarea::make('comment')
                    ->label('Comment')
                    ->rows(4)
                    ->maxLength(2000),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('address.order_no')->label('Order #')->sortable()->searchable(),
                BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ])
                    ->sortable(),
                ViewColumn::make('ratings')
                    ->label('Ratings')
                    ->view('filament.components.feedback-ratings'),
                TextColumn::make('created_at')->label('Submitted')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();
        return $user?->isAdmin() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedback::route('/'),
            'edit' => EditFeedback::route('/{record}/edit'),
        ];
    }
}
