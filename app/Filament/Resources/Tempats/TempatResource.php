<?php

namespace App\Filament\Resources\Tempats;

use App\Filament\Resources\Tempats\Pages\CreateTempat;
use App\Filament\Resources\Tempats\Pages\EditTempat;
use App\Filament\Resources\Tempats\Pages\ListTempats;
use App\Filament\Resources\Tempats\Pages\ViewTempat;
use App\Filament\Resources\Tempats\Schemas\TempatForm;
use App\Filament\Resources\Tempats\Schemas\TempatInfolist;
use App\Filament\Resources\Tempats\Tables\TempatsTable;
use App\Models\Tempat;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class TempatResource extends Resource
{
    protected static ?string $model = Tempat::class;



    protected static ?string $recordTitleAttribute = 'id';

    protected static string|UnitEnum|null $navigationGroup = 'Pengelolaan Pasar & Mitra';

    protected static ?string $navigationLabel = 'Tempat Usaha';
    protected static ?string $pluralModelLabel = 'Tempat Usaha';

protected static ?int $navigationSort = 6;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;

    public static function form(Schema $schema): Schema
    {
        return TempatForm::configure($schema);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function infolist(Schema $schema): Schema
    {
        return TempatInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TempatsTable::configure($table);
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
            'index' => ListTempats::route('/'),
            'create' => CreateTempat::route('/create'),
            'view' => ViewTempat::route('/{record}'),
            'edit' => EditTempat::route('/{record}/edit'),
        ];
    }
}
