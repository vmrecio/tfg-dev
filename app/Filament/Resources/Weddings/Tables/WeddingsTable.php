<?php

namespace App\Filament\Resources\Weddings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeddingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('event_date')
            ->columns([
                TextColumn::make('event_date')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('location')
                    ->label('Ubicación')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
