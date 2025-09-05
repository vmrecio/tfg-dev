<?php

namespace App\Filament\Resources\Weddings\Pages;

use App\Filament\Resources\Weddings\WeddingResource;
use App\Filament\Resources\Weddings\Schemas\WeddingForm;
use App\Models\User;
use App\Models\Wedding;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateWedding extends CreateRecord
{
    protected static string $resource = WeddingResource::class;

    private const DUPLICATE_PAIR_MESSAGE = 'Esta pareja ya tiene una boda creada en el sistema.';

    public function form(Schema $schema): Schema
    {
        return WeddingForm::create($schema);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Leer ids del estado del formulario (no confiar en $data porque no persistimos esos campos)
        $state = $this->form->getState();
        $id1 = $state['couple_member_1'] ?? null;
        $id2 = $state['couple_member_2'] ?? null;

        // Si hay pareja seleccionada, validar que la boda no exista antes de construir datos
        if ($id1 && $id2) {
            $this->coupleNotExists((int) $id1, (int) $id2);
        }

        $u1 = $id1 ? User::find($id1) : null;
        $u2 = $id2 ? User::find($id2) : null;

        // Calcular nombre/slug/firma cuando se hayan seleccionado los usuarios
        if ($u1 && $u2) {
            $first1 = trim($u1->name);
            $first2 = trim($u2->name);
            $data['name'] = $data['name'] ?? "Boda de {$first1} y {$first2}";

            $data['slug'] = $data['slug'] ?? $this->buildUniqueSlug($u1, $u2);

            $sig = $this->signatureFor((int) $id1, (int) $id2);
            if ($sig) {
                $data['pair_signature'] = $data['pair_signature'] ?? $sig;
            }
        }

        // Campos creados en el formulario, para mostrar los miembros como Nombre Apellido (Email)
        //no existen en la tabla y deben quitarse antes de que se haga el guardado
        unset($data['couple_member_1'], $data['couple_member_2']);

        return $data;
    }

    protected function afterCreate(): void
    {
        $record = $this->record;
        $state = $this->form->getState();
        $id1 = $state['couple_member_1'] ?? null;
        $id2 = $state['couple_member_2'] ?? null;

        logger()->info('IDs', ['id1' => $id1, 'id2' => $id2]);

        // Adjuntar usuarios como miembros de la pareja (sin datos extra en el pivot)
        $ids = array_filter([$id1, $id2]);
        if (! empty($ids)) {
            $record->users()->attach($ids);
        }

        // Persistir firma orden-independiente si no quedó seteada en mutate
        if ($id1 && $id2) {
            $sig = $this->signatureFor((int) $id1, (int) $id2);
            if ($sig && $record->pair_signature !== $sig) {
                $record->update(['pair_signature' => $sig]);
            }
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        // Primera comprobación que evita guardado si ya existe una boda de la pareja
        $state = $this->form->getState();
        $id1 = $state['couple_member_1'] ?? null;
        $id2 = $state['couple_member_2'] ?? null;
        if ($id1 && $id2) {
            $this->coupleNotExists((int) $id1, (int) $id2);
        }
        // Segunda comprobación capturando excepción en la creación
        try {
            $modelClass = $this->getModel();
            return $modelClass::create($data);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            if (str_contains($e->getMessage(), 'weddings_pair_signature_unique') ||
                str_contains($e->getMessage(), 'pair_signature')) {
                Notification::make()
                    ->title('No se pudo crear la boda')
                    ->body(self::DUPLICATE_PAIR_MESSAGE)
                    ->danger()
                    ->send();

                $this->addError($this->field('couple_member_1'), self::DUPLICATE_PAIR_MESSAGE);
                $this->addError($this->field('couple_member_2'), self::DUPLICATE_PAIR_MESSAGE);
                $this->halt();
            }
            throw $e;
        }
    }

    

    /**
     * Construye una firma canónica para la pareja, independientemente del orden.
     */
    private function signatureFor(?int $id1, ?int $id2): ?string
    {
        if (! $id1 || ! $id2) {
            return null;
        }
        return $id1 < $id2 ? ($id1.'-'.$id2) : ($id2.'-'.$id1);
    }

    /**
     * Construye un slug único a partir de dos usuarios (nombre + apellidos).
     */
    private function buildUniqueSlug(User $u1, User $u2): string
    {
        $full1 = trim($u1->name . ' ' . ($u1->last_name ?? ''));
        $full2 = trim($u2->name . ' ' . ($u2->last_name ?? ''));
        $base = str("boda de {$full1} y {$full2}")->slug()->toString();
        $slug = $base;
        $i = 1;
        while (Wedding::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }
        return $slug;
    }

    /**
     * Lanza error de validación si ya existe la pareja.
     */
    private function coupleNotExists(int $id1, int $id2): void
    {
        $sig = $this->signatureFor($id1, $id2);
        if ($sig && Wedding::where('pair_signature', $sig)->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $this->field('couple_member_1') => self::DUPLICATE_PAIR_MESSAGE,
                $this->field('couple_member_2') => self::DUPLICATE_PAIR_MESSAGE,
            ]);
        }
    }

    /**
     * Devuelve el path completo del campo dentro del estado del formulario (ejemplo: "data.couple_member_1").
     */
    private function field(string $name): string
    {
        return $this->form->getStatePath() . '.' . $name;
    }
}
