<?php

namespace App\Filament\Resources\EventKegiatans;

use App\Filament\Resources\EventKegiatans\Pages\CreateEventKegiatan;
use App\Filament\Resources\EventKegiatans\Pages\EditEventKegiatan;
use App\Filament\Resources\EventKegiatans\Pages\ListEventKegiatans;
use App\Filament\Resources\EventKegiatans\Pages\ViewEventKegiatan;
use App\Filament\Resources\EventKegiatans\Schemas\EventKegiatanForm;
use App\Filament\Resources\EventKegiatans\Schemas\EventKegiatanInfolist;
use App\Filament\Resources\EventKegiatans\Tables\EventKegiatansTable;
use App\Models\EventKegiatan;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;  
use UnitEnum;

class EventKegiatanResource extends Resource
{
    protected static ?string $model = EventKegiatan::class;


    protected static ?string $recordTitleAttribute = 'id';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CalendarDays;

    
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan & Pemberdayaan UMKM';
     protected static ?int $navigationSort = 12;
    public static function form(Schema $schema): Schema
    {
        return EventKegiatanForm::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function infolist(Schema $schema): Schema
    {
        return EventKegiatanInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventKegiatansTable::configure($table);
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
            'index' => ListEventKegiatans::route('/'),
            'create' => CreateEventKegiatan::route('/create'),
            'view' => ViewEventKegiatan::route('/{record}'),
            'edit' => EditEventKegiatan::route('/{record}/edit'),
        ];
    }
}
