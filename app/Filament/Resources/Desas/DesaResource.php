<?php

namespace App\Filament\Resources\Desas;

use App\Filament\Resources\Desas\Pages\CreateDesa;
use App\Filament\Resources\Desas\Pages\EditDesa;
use App\Filament\Resources\Desas\Pages\ListDesas;
use App\Filament\Resources\Desas\Pages\ViewDesa;
use App\Filament\Resources\Desas\Schemas\DesaForm;
use App\Filament\Resources\Desas\Schemas\DesaInfolist;
use App\Filament\Resources\Desas\Tables\DesasTable;
use App\Models\Desa;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DesaResource extends Resource
{
    protected static ?string $model = Desa::class;



    protected static string|UnitEnum|null $navigationGroup = 'Wilayah & Kepegawaian';
    protected static ?string $recordTitleAttribute = 'nama_desa';

protected static ?string $navigationLabel = 'Desa Kab Banjar';
protected static ?string $pluralModelLabel = 'Desa Kab Banjar';
protected static string|BackedEnum|null $navigationIcon = Heroicon::BuildingOffice2;
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Schema $schema): Schema
    {
        return DesaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DesaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DesasTable::configure($table);
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
            'index' => ListDesas::route('/'),
            'create' => CreateDesa::route('/create'),
            'view' => ViewDesa::route('/{record}'),
            'edit' => EditDesa::route('/{record}/edit'),
        ];
    }
}
