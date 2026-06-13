<?php

namespace App\Filament\Resources\DetailEvents;

use App\Filament\Resources\DetailEvents\Pages\CreateDetailEvent;
use App\Filament\Resources\DetailEvents\Pages\EditDetailEvent;
use App\Filament\Resources\DetailEvents\Pages\ListDetailEvents;
use App\Filament\Resources\DetailEvents\Pages\ViewDetailEvent;
use App\Filament\Resources\DetailEvents\Schemas\DetailEventForm;
use App\Filament\Resources\DetailEvents\Schemas\DetailEventInfolist;
use App\Filament\Resources\DetailEvents\Tables\DetailEventsTable;
use App\Models\DetailEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DetailEventResource extends Resource
{
    protected static ?string $model = DetailEvent::class;


    protected static ?string $recordTitleAttribute = 'id';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan & Pemberdayaan UMKM';
     protected static ?int $navigationSort = 16;
    public static function form(Schema $schema): Schema
    {
        return DetailEventForm::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function infolist(Schema $schema): Schema
    {
        return DetailEventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DetailEventsTable::configure($table);
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
            'index' => ListDetailEvents::route('/'),
            'create' => CreateDetailEvent::route('/create'),
            'view' => ViewDetailEvent::route('/{record}'),
            'edit' => EditDetailEvent::route('/{record}/edit'),
        ];
    }
}
