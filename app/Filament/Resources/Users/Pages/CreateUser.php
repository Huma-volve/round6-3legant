<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Contracts\Support\Htmlable;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
     public function getTitle(): string | Htmlable
    {
        return 'Create New User';
    }

    public function getHeading(): string | Htmlable
    {
        return 'Add New User';
    }
}
