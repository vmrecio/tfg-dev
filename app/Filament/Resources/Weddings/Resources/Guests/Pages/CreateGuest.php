<?php

namespace App\Filament\Resources\Weddings\Resources\Guests\Pages;

use App\Filament\Resources\Weddings\Resources\Guests\GuestResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGuest extends CreateRecord
{
    protected static string $resource = GuestResource::class;
}
