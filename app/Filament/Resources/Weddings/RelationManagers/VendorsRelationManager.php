<?php

namespace App\Filament\Resources\Weddings\RelationManagers;

use App\Models\Vendor;
use App\Models\VendorSpecialty;
use Filament\Actions\Action;
use Filament\Actions\DetachAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model as EloquentModel;

class VendorsRelationManager extends RelationManager
{
    protected static string $relationship = 'vendors';

    public static function getTitle(EloquentModel $ownerRecord, string $pageClass): string
    {
        return 'Servicios';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')->label('Empresa')->searchable(),
                TextColumn::make('specialty.display_name')->label('Servicio')->sortable()->searchable(),
                TextColumn::make('pivot.status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'declined' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('pivot.contract_amount')->label('Importe')->money('EUR', true),
            ])
            ->headerActions([
                Action::make('hireVendor')
                    ->label('Contratar servicio')
                    ->icon('heroicon-o-briefcase')
                    ->form(fn (Schema $schema) => $schema->components([
                        Select::make('vendor_specialty_id')
                            ->label('Servicio')
                            ->options(fn () => VendorSpecialty::orderBy('display_name')->pluck('display_name', 'id'))
                            ->required()
                            ->live(),
                        Select::make('vendor_id')
                            ->label('Proveedor')
                            ->options(function ($get) {
                                $spec = $get('vendor_specialty_id');
                                if (! $spec) return [];
                                return Vendor::where('vendor_specialty_id', $spec)
                                    ->orderBy('company_name')
                                    ->pluck('company_name', 'id');
                            })
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'declined' => 'Declined',
                            ])
                            ->required()
                            ->native(false),
                        TextInput::make('contract_amount')
                            ->label('Importe')
                            ->numeric()
                            ->prefix('€'),
                        Textarea::make('notes')
                            ->label('Notas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]))
                    ->action(function (array $data) {
                        $wedding = $this->getOwnerRecord();
                        $wedding->vendors()->syncWithoutDetaching([
                            $data['vendor_id'] => [
                                'status' => $data['status'],
                                'contract_amount' => $data['contract_amount'] ?? null,
                                'notes' => $data['notes'] ?? null,
                            ],
                        ]);
                    }),
            ])
            ->recordActions([
                DetachAction::make(),
            ]);
    }
}
