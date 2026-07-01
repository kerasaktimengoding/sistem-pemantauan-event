<?php

namespace App\Filament\Resources\PerbandinganWilayahs;

use App\Filament\Resources\PerbandinganWilayahs\Pages\CreatePerbandinganWilayah;
use App\Filament\Resources\PerbandinganWilayahs\Pages\EditPerbandinganWilayah;
use App\Filament\Resources\PerbandinganWilayahs\Pages\ListPerbandinganWilayahs;
use App\Filament\Resources\PerbandinganWilayahs\Pages\ViewPerbandinganWilayah;
use App\Filament\Resources\PerbandinganWilayahs\Schemas\PerbandinganWilayahForm;
use App\Filament\Resources\PerbandinganWilayahs\Schemas\PerbandinganWilayahInfolist;
use App\Filament\Resources\PerbandinganWilayahs\Tables\PerbandinganWilayahsTable;
use App\Models\PerbandinganWilayah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PerbandinganWilayahResource extends Resource
{
    protected static ?string $model = PerbandinganWilayah::class;


    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ArrowsUpDown;
          protected static string | UnitEnum | null $navigationGroup = 'Aktivitas Pemantauan Harga';
          protected static ?string $navigationLabel = 'Perbandingan Pasar';
    protected static ?string $pluralModelLabel = 'Perbandingan Pasar';
 protected static ?int $navigationSort = 17;

    public static function form(Schema $schema): Schema
    {
        return PerbandinganWilayahForm::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function infolist(Schema $schema): Schema
    {
        return PerbandinganWilayahInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerbandinganWilayahsTable::configure($table);
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
            'index' => ListPerbandinganWilayahs::route('/'),
            'create' => CreatePerbandinganWilayah::route('/create'),
            'view' => ViewPerbandinganWilayah::route('/{record}'),
            'edit' => EditPerbandinganWilayah::route('/{record}/edit'),
        ];
    }
}
