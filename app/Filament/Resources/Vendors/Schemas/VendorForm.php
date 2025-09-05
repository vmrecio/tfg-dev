<?php

namespace App\Filament\Resources\Vendors\Schemas;

use App\Models\VendorSpecialty;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VendorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('vendor_specialty_id')
                    ->label('Specialty')
                    ->options(fn () => VendorSpecialty::orderBy('display_name')->pluck('display_name', 'id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('company_name'),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('website'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
