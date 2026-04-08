<?php

namespace App\Filament\Resources\TrenHargas;

use App\Filament\Resources\TrenHargas\Pages\CreateTrenHarga;
use App\Filament\Resources\TrenHargas\Pages\EditTrenHarga;
use App\Filament\Resources\TrenHargas\Pages\ListTrenHargas;
use App\Filament\Resources\TrenHargas\Pages\ViewTrenHarga;
use App\Filament\Resources\TrenHargas\Schemas\TrenHargaForm;
use App\Filament\Resources\TrenHargas\Schemas\TrenHargaInfolist;
use App\Filament\Resources\TrenHargas\Tables\TrenHargasTable;
use App\Models\TrenHarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TrenHargaResource extends Resource
{
    protected static ?string $model = TrenHarga::class;


    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChartBarSquare;
      protected static string | UnitEnum | null $navigationGroup = 'DATA ANALISIS';
 protected static ?int $navigationSort = 16;

    public static function form(Schema $schema): Schema
    {
        return TrenHargaForm::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function infolist(Schema $schema): Schema
    {
        return TrenHargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TrenHargasTable::configure($table);
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
            'index' => ListTrenHargas::route('/'),
            'create' => CreateTrenHarga::route('/create'),
            'view' => ViewTrenHarga::route('/{record}'),
            'edit' => EditTrenHarga::route('/{record}/edit'),
        ];
    }
}
