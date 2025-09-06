<?php

namespace App\Filament\Resources\Weddings\RelationManagers;

use App\Filament\Resources\Weddings\Resources\TimeLineItems\TimeLineItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class TimelineItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'timelineItems';

    protected static ?string $relatedResource = TimeLineItemResource::class;

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return 'Time Line';
    }

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make()
                    ->label('Añadir evento al Time Line')
                    ->icon('heroicon-o-clock')
            ]);
    }
}
