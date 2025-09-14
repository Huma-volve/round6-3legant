<?php

namespace App\Filament\Resources\Coupons\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CouponForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->required(),
                TextInput::make('discount_value')
                    ->required()
                    ->numeric(),
                TextInput::make('max_use')
                    ->required()
                    ->numeric()
                    ->default(1),
                Toggle::make('is_activated')
                    ->required(),
                DateTimePicker::make('start_at'),
                DateTimePicker::make('end_at'),
            ]);
    }
}
