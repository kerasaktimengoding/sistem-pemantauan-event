<?php

namespace App\Filament\Resources\RekapHargas;

use App\Filament\Resources\RekapHargas\Pages\CreateRekapHarga;
use App\Filament\Resources\RekapHargas\Pages\EditRekapHarga;
use App\Filament\Resources\RekapHargas\Pages\ListRekapHargas;
use App\Filament\Resources\RekapHargas\Pages\ViewRekapHarga;
use App\Filament\Resources\RekapHargas\Schemas\RekapHargaForm;
use App\Filament\Resources\RekapHargas\Schemas\RekapHargaInfolist;
use App\Filament\Resources\RekapHargas\Tables\RekapHargasTable;
use App\Models\RekapHarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RekapHargaResource extends Resource
{
    protected static ?string $model = RekapHarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    protected static string | UnitEnum | null $navigationGroup = 'DATA ANALISIS';

 protected static ?int $navigationSort = 1;
    public static function form(Schema $schema): Schema
    {
        return RekapHargaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RekapHargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RekapHargasTable::configure($table);
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
            'index' => ListRekapHargas::route('/'),
            'create' => CreateRekapHarga::route('/create'),
            'view' => ViewRekapHarga::route('/{record}'),
            'edit' => EditRekapHarga::route('/{record}/edit'),
        ];
    }
}
