<?php

namespace App\Filament\Resources\Orders\RelationManagers;

use App\Models\OrderItem;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class OrderItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'orderItems'; 

    protected static ?string $recordTitleAttribute = 'id';
   public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')->label('Product'),
                TextColumn::make('quantity')->sortable(),
                TextColumn::make('price')->money('usd'),
                TextColumn::make('subtotal')->money('usd'),
            ]);
    }
}
