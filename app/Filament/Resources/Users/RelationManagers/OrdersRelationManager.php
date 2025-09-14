<?php
namespace App\Filament\Resources\Users\RelationManagers;

use App\Models\Order;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders'; 
        protected static ?string $recordTitleAttribute = 'id';

   public function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('id')->sortable(),
            TextColumn::make('status')->sortable(),
            TextColumn::make('total')->money('usd')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable(),
        ])
        ->actions([
            Action::make('view')->url(fn(Order $record) => route('orders.show', $record)),
        ])
        ->bulkActions([
            BulkAction::make('deleteSelected')->action(fn($records) => $records->each->delete()),
        ]);
}

}
