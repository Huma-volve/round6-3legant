<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Models\Review;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                //
                Select::make('user_id')
                ->relationship('user', 'fname')
                ->required(),
            TextInput::make('rating')
                ->numeric()
                ->minValue(1)
                ->maxValue(5)
                ->required(),
            Textarea::make('comment')->nullable(),
            ]);
    }

    public function table(Table $table): Table
    {
        // return Review::configure($table);
         return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user.fname')->label('User')->sortable(),
                TextColumn::make('rating')->sortable(),
                TextColumn::make('comment')->limit(30),
            ])
            ->actions([
                Action::make('edit')->url(fn (Review $record) => static::getUrl('edit', ['record' => $record])),
                Action::make('delete')->requiresConfirmation()->action(fn (Review $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('deleteSelected')->action(fn ($records) => $records->each->delete())->requiresConfirmation(),
            ]);
    }
}
