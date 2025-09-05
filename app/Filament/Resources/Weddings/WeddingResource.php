<?php

namespace App\Filament\Resources\Weddings;

use App\Filament\Resources\Weddings\Pages\CreateWedding;
use App\Filament\Resources\Weddings\Pages\EditWedding;
use App\Filament\Resources\Weddings\Pages\ListWeddings;
use App\Filament\Resources\Weddings\Schemas\WeddingForm;
use App\Filament\Resources\Weddings\Tables\WeddingsTable;
use App\Models\Wedding;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

use App\Filament\Resources\Weddings\RelationManagers\GuestsRelationManager;

class WeddingResource extends Resource
{
    protected static ?string $model = Wedding::class;

    protected static ?string $modelLabel = 'Boda';
    protected static ?string $pluralModelLabel = 'Bodas';
    protected static ?string $navigationLabel = 'Bodas';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return WeddingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeddingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GuestsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWeddings::route('/'),
            'create' => CreateWedding::route('/create'),
            'edit' => EditWedding::route('/{record}/edit'),
        ];
    }
}
