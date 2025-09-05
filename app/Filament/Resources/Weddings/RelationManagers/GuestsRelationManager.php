<?php

namespace App\Filament\Resources\Weddings\RelationManagers;

use App\Filament\Resources\Weddings\Resources\Guests\GuestResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class GuestsRelationManager extends RelationManager
{
    protected static string $relationship = 'guests';

    protected static ?string $relatedResource = GuestResource::class;

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return 'Invitados';
    }


    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir invitado')
                    ->icon('heroicon-o-envelope')
            ]);
    }
}
