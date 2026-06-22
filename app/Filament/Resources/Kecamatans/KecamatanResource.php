<?php

namespace App\Filament\Resources\Kecamatans;

use App\Filament\Resources\Kecamatans\Pages\CreateKecamatan;
use App\Filament\Resources\Kecamatans\Pages\EditKecamatan;
use App\Filament\Resources\Kecamatans\Pages\ListKecamatans;
use App\Filament\Resources\Kecamatans\Pages\ViewKecamatan;
use App\Filament\Resources\Kecamatans\Schemas\KecamatanForm;
use App\Filament\Resources\Kecamatans\Schemas\KecamatanInfolist;
use App\Filament\Resources\Kecamatans\Tables\KecamatansTable;
use App\Models\Kecamatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static ?string $recordTitleAttribute = 'nama_kecamatan';
    protected static string|UnitEnum|null $navigationGroup = 'Wilayah & Kepegawaian';
    protected static ?string $navigationLabel = 'Kecamatan Kab Banjar';
    protected static ?string $pluralModelLabel = 'Kecamatan Kab Banjar';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Map;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return KecamatanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KecamatanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KecamatansTable::configure($table);
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
            'index' => ListKecamatans::route('/'),
            'create' => CreateKecamatan::route('/create'),
            'view' => ViewKecamatan::route('/{record}'),
            'edit' => EditKecamatan::route('/{record}/edit'),
        ];
    }
}
