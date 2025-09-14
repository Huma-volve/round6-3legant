<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Actions\Action; 

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions;


class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $label = 'Users';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
protected static ?string $navigationLabel = 'Users';
protected static ?string $pluralLabel = 'Users';
protected static ?string $modelLabel = 'User';

    protected static ?string $recordTitleAttribute = 'fname';

public static function form(Schema $schema): Schema
{
    return $schema
        ->components([
            TextInput::make('fname')->required()->label('First Name'),
            TextInput::make('lname')->required()->label('Last Name'),
            TextInput::make('email')->email()->required(),
            TextInput::make('phone')->tel(),
            TextInput::make('role'),
            Toggle::make('is_verified')->label('Verified'),
        ]);
}
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('fname')->label('First Name')->searchable(),
            TextColumn::make('lname')->label('Last Name')->searchable(),
            TextColumn::make('email')->searchable(),
            TextColumn::make('phone'),
            BooleanColumn::make('is_verified')->label('Verified'),
            TextColumn::make('role'),
        ])
        ->filters([
        ])
       
    ->actions([
    // Action::make('edit')
        \Filament\Actions\Action::make('edit')
        ->label('Edit')
        ->icon('heroicon-o-pencil')
->url(fn (User $record): string => static::getUrl('edit', ['record' => $record]))
        ->openUrlInNewTab(),

    // Action::make('delete')
         \Filament\Actions\Action::make('delete')
        ->label('Delete')
        ->icon('heroicon-o-trash')
        ->requiresConfirmation()
        ->action(fn (User $record) => $record->delete()),
])
->bulkActions([
      \Filament\Actions\BulkAction::make('deleteSelected') 
        ->label('Delete Selected')
        ->action(fn ($records) => $records->each->delete())
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
    return 'User-Managment';
}

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
    public static function getTitle(?User $record): string
{
    if ($record) {
        return $record->fname . ' ' . $record->lname;
    }

    return 'User';
}


}
