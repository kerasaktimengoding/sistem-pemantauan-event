<?php

namespace App\Filament\Resources\Pedagangs;

use App\Filament\Resources\Pedagangs\Pages\CreatePedagang;
use App\Filament\Resources\Pedagangs\Pages\EditPedagang;
use App\Filament\Resources\Pedagangs\Pages\ListPedagangs;
use App\Filament\Resources\Pedagangs\Pages\ViewPedagang;
use App\Filament\Resources\Pedagangs\Schemas\PedagangForm;
use App\Filament\Resources\Pedagangs\Schemas\PedagangInfolist;
use App\Filament\Resources\Pedagangs\Tables\PedagangsTable;
use App\Models\Pedagang;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PedagangResource extends Resource
{
    protected static ?string $model = Pedagang::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PedagangForm::configure($schema);
    }
    protected static string | UnitEnum | null $navigationGroup = 'DATA MASTER';
    protected static ?int $navigationSort = 8;
    public static function infolist(Schema $schema): Schema
    {
        return PedagangInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PedagangsTable::configure($table);
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
            'index' => ListPedagangs::route('/'),
            'create' => CreatePedagang::route('/create'),
            'view' => ViewPedagang::route('/{record}'),
            'edit' => EditPedagang::route('/{record}/edit'),
        ];
    }
}
