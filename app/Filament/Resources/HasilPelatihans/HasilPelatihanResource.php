<?php

namespace App\Filament\Resources\HasilPelatihans;

use App\Filament\Resources\HasilPelatihans\Pages\CreateHasilPelatihan;
use App\Filament\Resources\HasilPelatihans\Pages\EditHasilPelatihan;
use App\Filament\Resources\HasilPelatihans\Pages\ListHasilPelatihans;
use App\Filament\Resources\HasilPelatihans\Pages\ViewHasilPelatihan;
use App\Filament\Resources\HasilPelatihans\Schemas\HasilPelatihanForm;
use App\Filament\Resources\HasilPelatihans\Schemas\HasilPelatihanInfolist;
use App\Filament\Resources\HasilPelatihans\Tables\HasilPelatihansTable;
use App\Models\HasilPelatihan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HasilPelatihanResource extends Resource
{
    protected static ?string $model = HasilPelatihan::class;


    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan & Pemberdayaan UMKM';
     protected static ?int $navigationSort = 15;
    public static function form(Schema $schema): Schema
    {
        return HasilPelatihanForm::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function infolist(Schema $schema): Schema
    {
        return HasilPelatihanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HasilPelatihansTable::configure($table);
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
            'index' => ListHasilPelatihans::route('/'),
            'create' => CreateHasilPelatihan::route('/create'),
            'view' => ViewHasilPelatihan::route('/{record}'),
            'edit' => EditHasilPelatihan::route('/{record}/edit'),
        ];
    }
}
