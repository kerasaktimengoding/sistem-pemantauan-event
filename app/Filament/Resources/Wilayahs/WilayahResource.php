<?php

namespace App\Filament\Resources\Wilayahs;

use App\Filament\Resources\Wilayahs\Pages\CreateWilayah;
use App\Filament\Resources\Wilayahs\Pages\EditWilayah;
use App\Filament\Resources\Wilayahs\Pages\ListWilayahs;
use App\Filament\Resources\Wilayahs\Pages\ViewWilayah;
use App\Filament\Resources\Wilayahs\Schemas\WilayahForm;
use App\Filament\Resources\Wilayahs\Schemas\WilayahInfolist;
use App\Filament\Resources\Wilayahs\Tables\WilayahsTable;
use App\Models\Wilayah;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use App\Filament\Resources\Wilayahs\Widgets\WilayahWidget;

class WilayahResource extends Resource
{
    protected static ?string $model = Wilayah::class;

protected static ?string $recordTitleAttribute = 'kode_wilayah';
protected static string | UnitEnum | null $navigationGroup = 'Wilayah & Kepegawaian';
protected static ?string $navigationLabel = 'Zonasi Pasar Kab Banjar';
protected static ?string $pluralModelLabel = 'Zonasi Pasar Kab Banjar';

protected static ?int $navigationSort = 3;

   
    public static function form(Schema $schema): Schema
    {
        return WilayahForm::configure($schema);
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static string|BackedEnum|null $navigationIcon = Heroicon::MapPin;
    public static function infolist(Schema $schema): Schema
    {
        return WilayahInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WilayahsTable::configure($table);
    }

    public static function getWidgets(): array
    {
        return [
            WilayahWidget::class,
        ];
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
            'index' => ListWilayahs::route('/'),
            'create' => CreateWilayah::route('/create'),
            'view' => ViewWilayah::route('/{record}'),
            'edit' => EditWilayah::route('/{record}/edit'),
        ];
    }
}
