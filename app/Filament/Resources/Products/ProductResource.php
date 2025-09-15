<?php

namespace App\Filament\Resources\Products;

use App\Filament\Resources\Products\Pages\CreateProduct;
use App\Filament\Resources\Products\Pages\EditProduct;
use App\Filament\Resources\Products\Pages\ListProducts;
use App\Filament\Resources\Products\Schemas\ProductForm;
use App\Filament\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use App\Filament\Resources\Products\RelationManagers\ReviewsRelationManager;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Products';

    protected static ?string $pluralLabel = 'Products';

    protected static ?string $modelLabel = 'Product';

    public static function form(Schema $schema): Schema
    {
        // return ProductForm::configure($schema);
         return $schema->components([
            TextInput::make('name')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->rows(4)
                ->nullable(),

            TextInput::make('price')
                ->numeric()
                ->required(),

            TextInput::make('stock')
                ->numeric()
                ->default(0),

            KeyValue::make('add_info')
                ->label('Additional Info')
                ->keyLabel('Attribute')
                ->valueLabel('Value')
                ->addButtonLabel('Add New Info')
                ->nullable(),

            Select::make('subcategory_id')
                ->relationship('subcategory', 'name')
                ->label('Subcategory')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        // return ProductsTable::configure($table);
         return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('price')->money('usd')->sortable(),
                TextColumn::make('stock')->sortable(),
                BadgeColumn::make('subcategory.name')
                    ->label('Category')
                    ->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Product $record): string => static::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (Product $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('deleteSelected')
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
        ReviewsRelationManager::class,

        ];
    }

    public static function getNavigationGroup(): ?string
{
    return 'E-Commerce';
}

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
