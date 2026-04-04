<?php

namespace App\Filament\Resources\Pasars;

use App\Filament\Resources\Pasars\Pages\CreatePasar;
use App\Filament\Resources\Pasars\Pages\EditPasar;
use App\Filament\Resources\Pasars\Pages\ListPasars;
use App\Filament\Resources\Pasars\Pages\ViewPasar;
use App\Filament\Resources\Pasars\Schemas\PasarForm;
use App\Filament\Resources\Pasars\Schemas\PasarInfolist;
use App\Filament\Resources\Pasars\Tables\PasarsTable;
use App\Models\Pasar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PasarResource extends Resource
{
    protected static ?string $model = Pasar::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'yes';

    public static function form(Schema $schema): Schema
    {
        return PasarForm::configure($schema);
    }
    protected static string | UnitEnum | null $navigationGroup = 'DATA MASTER';
    protected static ?int $navigationSort = 7;

    public static function infolist(Schema $schema): Schema
    {
        return PasarInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PasarsTable::configure($table);
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
            'index' => ListPasars::route('/'),
            'create' => CreatePasar::route('/create'),
            'view' => ViewPasar::route('/{record}'),
            'edit' => EditPasar::route('/{record}/edit'),
        ];
    }
}
