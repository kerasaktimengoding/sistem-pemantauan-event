<?php

namespace App\Filament\Resources\Satuans;

use App\Filament\Resources\Satuans\Pages\CreateSatuan;
use App\Filament\Resources\Satuans\Pages\EditSatuan;
use App\Filament\Resources\Satuans\Pages\ListSatuans;
use App\Filament\Resources\Satuans\Pages\ViewSatuan;
use App\Filament\Resources\Satuans\Schemas\SatuanForm;
use App\Filament\Resources\Satuans\Schemas\SatuanInfolist;
use App\Filament\Resources\Satuans\Tables\SatuansTable;
use App\Models\Satuan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class SatuanResource extends Resource
{
    protected static ?string $model = Satuan::class;
protected static string|BackedEnum|null $navigationIcon = Heroicon::Scale;

    protected static ?string $recordTitleAttribute = 'yes';
        protected static string | UnitEnum | null $navigationGroup = 'DATA MASTER';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SatuanForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SatuanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SatuansTable::configure($table);
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
            'index' => ListSatuans::route('/'),
            'create' => CreateSatuan::route('/create'),
            'view' => ViewSatuan::route('/{record}'),
            'edit' => EditSatuan::route('/{record}/edit'),
        ];
    }
}
