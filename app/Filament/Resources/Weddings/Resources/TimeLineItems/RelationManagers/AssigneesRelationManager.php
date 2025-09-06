<?php

namespace App\Filament\Resources\Weddings\Resources\TimeLineItems\RelationManagers;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class AssigneesRelationManager extends RelationManager
{
    protected static string $relationship = 'assignees';

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return 'Asignados';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Nombre')->searchable(),
                TextColumn::make('last_name')->label('Apellidos')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
            ])
            ->headerActions([
                Action::make('addAssignee')
                    ->label('Añadir asignado')
                    ->icon('heroicon-o-user-plus')
                    ->form(fn (Schema $schema) => $schema->components([
                        Select::make('user_id')
                            ->label('Usuario')
                            ->options(fn () => $this->getAssignableUserOptions())
                            ->searchable()
                            ->preload()
                            ->required(),
                    ]))
                    ->action(function (array $data) {
                        $item = $this->getOwnerRecord();
                        $item->assignees()->syncWithoutDetaching([$data['user_id']]);
                    }),
            ])
            ->recordActions([
                DetachAction::make(),
            ]);
    }

    private function getAssignableUserOptions(): array
    {
        $item = $this->getOwnerRecord();
        $weddingId = (int) $item->wedding_id;

        $users = User::query()
            ->select('users.id', 'users.name', 'users.last_name', 'users.email')
            ->whereExists(function ($q) use ($weddingId) {
                $q->from('vendors')
                    ->whereColumn('vendors.user_id', 'users.id')
                    ->whereExists(function ($q2) use ($weddingId) {
                        $q2->from('wedding_vendor')
                            ->whereColumn('wedding_vendor.vendor_id', 'vendors.id')
                            ->where('wedding_vendor.wedding_id', $weddingId)
                            ->where('wedding_vendor.status', 'confirmed');
                    });
            })
            ->orderBy('users.name')
            ->orderBy('users.last_name')
            ->get();

        return $users->mapWithKeys(function ($u) {
            $label = trim($u->name . ' ' . ($u->last_name ?? '')) . ' (' . $u->email . ')';
            return [$u->id => $label];
        })->toArray();
    }
}
