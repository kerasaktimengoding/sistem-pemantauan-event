<?php

namespace App\Filament\Resources\Komoditas;

use App\Filament\Resources\Komoditas\Pages\CreateKomoditas;
use App\Filament\Resources\Komoditas\Pages\EditKomoditas;
use App\Filament\Resources\Komoditas\Pages\ListKomoditas;
use App\Filament\Resources\Komoditas\Pages\ViewKomoditas;
use App\Filament\Resources\Komoditas\Schemas\KomoditasForm;
use App\Filament\Resources\Komoditas\Schemas\KomoditasInfolist;
use App\Filament\Resources\Komoditas\Tables\KomoditasTable;
use App\Models\Komoditas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KomoditasResource extends Resource
{
    protected static ?string $model = Komoditas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';
protected static string | UnitEnum | null $navigationGroup = 'DATA MASTER';
    public static function form(Schema $schema): Schema
    {
        return KomoditasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KomoditasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KomoditasTable::configure($table);
    }
     protected static ?int $navigationSort = 4;
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKomoditas::route('/'),
            'create' => CreateKomoditas::route('/create'),
            'view' => ViewKomoditas::route('/{record}'),
            'edit' => EditKomoditas::route('/{record}/edit'),
        ];
    }
}
