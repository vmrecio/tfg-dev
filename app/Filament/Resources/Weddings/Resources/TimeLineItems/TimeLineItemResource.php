<?php

namespace App\Filament\Resources\Weddings\Resources\TimeLineItems;

use App\Filament\Resources\Weddings\Resources\TimeLineItems\Pages\CreateTimeLineItem;
use App\Filament\Resources\Weddings\Resources\TimeLineItems\Pages\EditTimeLineItem;
use App\Filament\Resources\Weddings\Resources\TimeLineItems\Schemas\TimeLineItemForm;
use App\Filament\Resources\Weddings\Resources\TimeLineItems\Tables\TimeLineItemsTable;
use App\Filament\Resources\Weddings\WeddingResource;
use App\Models\TimeLineItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TimeLineItemResource extends Resource
{
    protected static ?string $model = TimeLineItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = WeddingResource::class;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return TimeLineItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TimeLineItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateTimeLineItem::route('/create'),
            'edit' => EditTimeLineItem::route('/{record}/edit'),
        ];
    }
}
