<?php

namespace App\Filament\Resources\KategoriKomoditas;

use App\Filament\Resources\KategoriKomoditas\Pages\CreateKategoriKomoditas;
use App\Filament\Resources\KategoriKomoditas\Pages\EditKategoriKomoditas;
use App\Filament\Resources\KategoriKomoditas\Pages\ListKategoriKomoditas;
use App\Filament\Resources\KategoriKomoditas\Pages\ViewKategoriKomoditas;
use App\Filament\Resources\KategoriKomoditas\Schemas\KategoriKomoditasForm;
use App\Filament\Resources\KategoriKomoditas\Schemas\KategoriKomoditasInfolist;
use App\Filament\Resources\KategoriKomoditas\Tables\KategoriKomoditasTable;
use App\Models\KategoriKomoditas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KategoriKomoditasResource extends Resource
{
    protected static ?string $model = KategoriKomoditas::class;
protected static string | UnitEnum | null $navigationGroup = 'DATA MASTER';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
     protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return KategoriKomoditasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KategoriKomoditasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KategoriKomoditasTable::configure($table);
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
            'index' => ListKategoriKomoditas::route('/'),
            'create' => CreateKategoriKomoditas::route('/create'),
            'view' => ViewKategoriKomoditas::route('/{record}'),
            'edit' => EditKategoriKomoditas::route('/{record}/edit'),
        ];
    }
}
