<?php

namespace App\Filament\Resources\Weddings\Pages;

use App\Filament\Resources\Weddings\WeddingResource;
use App\Filament\Resources\Weddings\Schemas\WeddingForm;
use App\Models\VendorSpecialty;
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

    protected function afterSave(): void
    {
        $state = $this->form->getState();
        $record = $this->record; // Wedding

        foreach (VendorSpecialty::pluck('id') as $specId) {
            $field = 'vendor_specialty_' . $specId;
            $selectedVendorId = $state[$field] ?? null;

            $currentIds = $record->vendors()
                ->where('vendor_specialty_id', $specId)
                ->pluck('vendors.id')
                ->all();

            if ($selectedVendorId) {
                $toDetach = array_diff($currentIds, [$selectedVendorId]);
                if (! empty($toDetach)) {
                    $record->vendors()->detach($toDetach);
                }
                if (! in_array($selectedVendorId, $currentIds, true)) {
                    $record->vendors()->attach([$selectedVendorId => ['status' => 'confirmed']]);
                }
            } else {
                if (! empty($currentIds)) {
                    $record->vendors()->detach($currentIds);
                }
            }
        }
    }
}

