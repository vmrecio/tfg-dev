<?php

namespace App\Filament\Resources\Weddings\Pages;

use App\Filament\Resources\Weddings\WeddingResource;
use App\Filament\Resources\Weddings\Schemas\WeddingForm;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Schema;

class EditWedding extends EditRecord
{
    protected static string $resource = WeddingResource::class;

    public function form(Schema $schema): Schema
    {
        return WeddingForm::edit($schema);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
