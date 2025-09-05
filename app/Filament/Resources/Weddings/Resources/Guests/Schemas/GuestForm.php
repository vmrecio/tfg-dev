<?php

namespace App\Filament\Resources\Weddings\Resources\Guests\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GuestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('last_name'),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('side'),
                TextInput::make('group'),
                TextInput::make('table_name'),
                TextInput::make('seats')
                    ->required()
                    ->numeric()
                    ->default(1),
                DateTimePicker::make('invitation_sent_at'),
                Select::make('rsvp_status')
                    ->options(['pending' => 'Pending', 'accepted' => 'Accepted', 'declined' => 'Declined'])
                    ->default('pending')
                    ->required(),
                Textarea::make('dietary')
                    ->columnSpanFull(),
                Textarea::make('notes')
                    ->columnSpanFull(),
                DateTimePicker::make('responded_at'),
            ]);
    }
}
