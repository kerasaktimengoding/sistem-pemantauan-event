<?php

namespace App\Filament\Resources\KehadiranEvents;

use App\Filament\Resources\KehadiranEvents\Pages\CreateKehadiranEvent;
use App\Filament\Resources\KehadiranEvents\Pages\EditKehadiranEvent;
use App\Filament\Resources\KehadiranEvents\Pages\ListKehadiranEvents;
use App\Filament\Resources\KehadiranEvents\Pages\ViewKehadiranEvent;
use App\Filament\Resources\KehadiranEvents\Schemas\KehadiranEventForm;
use App\Filament\Resources\KehadiranEvents\Schemas\KehadiranEventInfolist;
use App\Filament\Resources\KehadiranEvents\Tables\KehadiranEventsTable;
use App\Models\KehadiranEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class KehadiranEventResource extends Resource
{
    protected static ?string $model = KehadiranEvent::class;

  
    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return KehadiranEventForm::configure($schema);
    }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckBadge;
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan & Pemberdayaan UMKM';
     protected static ?int $navigationSort = 14;

    public static function infolist(Schema $schema): Schema
    {
        return KehadiranEventInfolist::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function table(Table $table): Table
    {
        return KehadiranEventsTable::configure($table);
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
            'index' => ListKehadiranEvents::route('/'),
            'create' => CreateKehadiranEvent::route('/create'),
            'view' => ViewKehadiranEvent::route('/{record}'),
            'edit' => EditKehadiranEvent::route('/{record}/edit'),
        ];
    }
}
