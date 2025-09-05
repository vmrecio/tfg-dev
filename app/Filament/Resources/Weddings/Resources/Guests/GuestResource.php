<?php

namespace App\Filament\Resources\Weddings\Resources\Guests;

use App\Filament\Resources\Weddings\Resources\Guests\Pages\CreateGuest;
use App\Filament\Resources\Weddings\Resources\Guests\Pages\EditGuest;
use App\Filament\Resources\Weddings\Resources\Guests\Schemas\GuestForm;
use App\Filament\Resources\Weddings\Resources\Guests\Tables\GuestsTable;
use App\Filament\Resources\Weddings\WeddingResource;
use App\Models\Guest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GuestResource extends Resource
{
    protected static ?string $model = Guest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = WeddingResource::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return GuestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GuestsTable::configure($table);
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
            'create' => CreateGuest::route('/create'),
            'edit' => EditGuest::route('/{record}/edit'),
        ];
    }
}
