<?php

namespace App\Filament\Resources\Pegawais;

use App\Filament\Resources\Pegawais\Pages\CreatePegawai;
use App\Filament\Resources\Pegawais\Pages\EditPegawai;
use App\Filament\Resources\Pegawais\Pages\ListPegawais;
use App\Filament\Resources\Pegawais\Pages\ViewPegawai;
use App\Filament\Resources\Pegawais\Schemas\PegawaiForm;
use App\Filament\Resources\Pegawais\Schemas\PegawaiInfolist;
use App\Filament\Resources\Pegawais\Tables\PegawaisTable;
use App\Models\Pegawai;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;


    
    protected static ?string $recordTitleAttribute = 'nama_pegawai';
protected static string | UnitEnum | null $navigationGroup = 'Wilayah & Kepegawaian';
protected static ?string $navigationLabel = 'Profil Kepegawaian';
protected static ?string $pluralModelLabel = 'Profil Kepegawaian';
protected static string|BackedEnum|null $navigationIcon = Heroicon::Users;
protected static ?int $navigationSort = 5;
    public static function form(Schema $schema): Schema
    {
        return PegawaiForm::configure($schema);
    }

    

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}
    public static function infolist(Schema $schema): Schema
    {
        return PegawaiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PegawaisTable::configure($table);
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
            'index' => ListPegawais::route('/'),
            'create' => CreatePegawai::route('/create'),
            'view' => ViewPegawai::route('/{record}'),
            'edit' => EditPegawai::route('/{record}/edit'),
        ];
    }
}
