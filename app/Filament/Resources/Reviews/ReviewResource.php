<?php

namespace App\Filament\Resources\Reviews;

use App\Filament\Resources\Reviews\Pages\CreateReview;
use App\Filament\Resources\Reviews\Pages\EditReview;
use App\Filament\Resources\Reviews\Pages\ListReviews;
use App\Filament\Resources\Reviews\Schemas\ReviewForm;
use App\Filament\Resources\Reviews\Tables\ReviewsTable;
use App\Models\Review;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'comment';
    protected static ?string $navigationLabel = 'Reviews';
    protected static ?string $pluralLabel = 'Reviews';
    protected static ?string $modelLabel = 'Review';
    public static function form(Schema $schema): Schema
    {
        // return ReviewForm::configure($schema);
        return $schema->components([
            Select::make('user_id')
                ->relationship('user', 'fname')
                    ->getOptionLabelUsing(fn ($user) => $user->full_name) 
                ->searchable()
                ->required(),

            Select::make('product_id')
                ->relationship('product', 'name')
                ->searchable()
                ->required(),

            TextInput::make('rating')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->required(),

            Textarea::make('comment')
                ->rows(3)
                ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        // return ReviewsTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.full_name')->label('User')->sortable(),
                TextColumn::make('product.name')->label('Product')->sortable(),
                // TextColumn::make('rating')->sortable(),
                TextColumn::make('rating')
                    ->label('Rating')
                    ->formatStateUsing(fn($state) => str_repeat('⭐', $state))
                    ->sortable(),

                TextColumn::make('comment')->limit(30),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn(Review $record): string => static::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn(Review $record) => $record->delete()),
            ])->bulkActions([
                BulkAction::make('deleteSelected')
                    ->label('Delete Selected')
                    ->action(fn($records) => $records->each->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

       public static function getNavigationGroup(): ?string
{
    return 'Support';
}

    public static function getPages(): array
    {
        return [
            'index' => ListReviews::route('/'),
            'create' => CreateReview::route('/create'),
            'edit' => EditReview::route('/{record}/edit'),
        ];
    }
}
