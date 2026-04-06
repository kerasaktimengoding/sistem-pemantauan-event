<?php

namespace App\Filament\Resources\InputHargas;

use App\Filament\Resources\InputHargas\Pages\CreateInputHarga;
use App\Filament\Resources\InputHargas\Pages\EditInputHarga;
use App\Filament\Resources\InputHargas\Pages\ListInputHargas;
use App\Filament\Resources\InputHargas\Pages\ViewInputHarga;
use App\Filament\Resources\InputHargas\Schemas\InputHargaForm;
use App\Filament\Resources\InputHargas\Schemas\InputHargaInfolist;
use App\Filament\Resources\InputHargas\Tables\InputHargasTable;
use App\Models\InputHarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class InputHargaResource extends Resource
{
    protected static ?string $model = InputHarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return InputHargaForm::configure($schema);
    }

    
    protected static string | UnitEnum | null $navigationGroup = 'DATA OPERASIONAL';
     protected static ?int $navigationSort = 1;
    public static function infolist(Schema $schema): Schema
    {
        return InputHargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InputHargasTable::configure($table);
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
            'index' => ListInputHargas::route('/'),
            'create' => CreateInputHarga::route('/create'),
            'view' => ViewInputHarga::route('/{record}'),
            'edit' => EditInputHarga::route('/{record}/edit'),
        ];
    }
}
