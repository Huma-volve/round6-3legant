<?php

namespace App\Filament\Resources\Orders;

use App\Filament\Resources\Orders\Pages\CreateOrder;
use App\Filament\Resources\Orders\Pages\EditOrder;
use App\Filament\Resources\Orders\Pages\ListOrders;
use App\Filament\Resources\Orders\Schemas\OrderForm;
use App\Filament\Resources\Orders\Tables\OrdersTable;
use App\Models\Order;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use App\Filament\Resources\Orders\RelationManagers\OrderItemsRelationManager;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'user.full_name';
    protected static ?string $navigationLabel = 'Orders';
    protected static ?string $pluralLabel =  'Orders';
    protected static ?string $modelLabel =  'Order';
    public static function form(Schema $schema): Schema
    {
        // return OrderForm::configure($schema);
         return $schema->components([
            TextInput::make('user_id')
                ->numeric()
                ->required(),

            TextInput::make('total_price')
                ->numeric()
                ->required(),

            Select::make('status')
                ->options([
                    'pending'   => 'Pending',
                    'shipped'   => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                ])
                ->required(),

            Select::make('payment_status')
                ->options([
                    'unpaid' => 'Unpaid',
                    'paid'   => 'Paid',
                    'failed' => 'Failed',
                ])
                ->required(),

            TextInput::make('address_id')
                ->numeric(),

            TextInput::make('coupon_id')
                ->numeric(),
        ]);
    }

    public static function table(Table $table): Table
    {
        // return OrdersTable::configure($table);
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('user_id')->sortable(),
                  TextColumn::make('user.full_name') 
                ->label('User')
                ->sortable()
                ->searchable(),
                TextColumn::make('total_price')->money('usd')->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('payment_status')->badge(),
                TextColumn::make('address_id'),
                TextColumn::make('coupon_id'),
            ])
            ->actions([
              \Filament\Actions\Action::make('edit')
        ->label('Edit')
        ->icon('heroicon-o-pencil')
->url(fn (Order $record): string => static::getUrl('edit', ['record' => $record]))
        ->openUrlInNewTab(),

    // Action::make('delete')
         \Filament\Actions\Action::make('delete')
        ->label('Delete')
        ->icon('heroicon-o-trash')
        ->requiresConfirmation()
        ->action(fn (Order $record) => $record->delete()),
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
                   OrderItemsRelationManager::class,

        ];
    }

        public static function getNavigationGroup(): ?string
{
    return 'E-Commerce';
}

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'create' => CreateOrder::route('/create'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }
}
