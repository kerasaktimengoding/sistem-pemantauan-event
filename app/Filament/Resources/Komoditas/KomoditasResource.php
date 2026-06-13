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
use App\Filament\Resources\Komoditas\Widgets\KomoditasWidget;

class KomoditasResource extends Resource
{
    protected static ?string $model = Komoditas::class;

    protected static ?string $recordTitleAttribute = 'nama_komoditas';
protected static string | UnitEnum | null $navigationGroup = 'Pengelolaan Pasar & Mitra';
protected static ?string $navigationLabel = 'Katalog Bahan Pokok';
protected static ?string $pluralModelLabel = 'Katalog Bahan Pokok';
protected static string|BackedEnum|null $navigationIcon = Heroicon::ShoppingBag;
protected static ?int $navigationSort = 7;

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
     
       public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

       public static function getWidgets(): array
    {
        return [
            KomoditasWidget::class,
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
