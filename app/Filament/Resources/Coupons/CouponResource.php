<?php

namespace App\Filament\Resources\Coupons;

use App\Filament\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Resources\Coupons\Pages\ListCoupons;
use App\Filament\Resources\Coupons\Schemas\CouponForm;
use App\Filament\Resources\Coupons\Tables\CouponsTable;
use App\Models\Coupon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'code';
  protected static ?string $navigationLabel = 'Coupons';
    protected static ?string $pluralLabel = 'Coupons';
    protected static ?string $modelLabel = 'Coupon';
    public static function form(Schema $schema): Schema
    {
        // return CouponForm::configure($schema);
         return $schema->components([
            TextInput::make('code')
                ->label('Coupon Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(50),

            TextInput::make('discount_value')
                ->label('Discount Value')
                ->numeric()
                ->required(),

            TextInput::make('max_use')
                ->label('Max Usage')
                ->numeric()
                ->required(),

            Toggle::make('is_activated')
                ->label('Activated')
                ->default(true),

            DateTimePicker::make('start_at')
                ->label('Start At')
                ->required(),

            DateTimePicker::make('end_at')
                ->label('End At')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        // return CouponsTable::configure($table);
         return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('code')->searchable()->sortable(),
                TextColumn::make('discount_value')
                    ->label('Discount')
                    ->sortable(),
                TextColumn::make('max_use')->sortable(),
                IconColumn::make('is_activated')
                    ->boolean()
                    ->label('Active'),
                TextColumn::make('start_at')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('end_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->actions([
                Action::make('edit')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (Coupon $record): string => static::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab(),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->action(fn (Coupon $record) => $record->delete()),
            ])
            ->bulkActions([
                BulkAction::make('deleteSelected')
                    ->label('Delete Selected')
                    ->action(fn ($records) => $records->each->delete())
                    ->requiresConfirmation()
                    ->color('danger'),
            ]);
    }

 public static function getNavigationGroup(): ?string
{
    return 'E-Commerce';
}

    public static function getRelations(): array
    {
        return [
            //

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
