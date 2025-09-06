<?php

namespace App\Filament\Resources\Weddings\Resources\TimeLineItems\Pages;

use App\Filament\Resources\Weddings\Resources\TimeLineItems\TimeLineItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTimeLineItem extends EditRecord
{
    protected static string $resource = TimeLineItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
