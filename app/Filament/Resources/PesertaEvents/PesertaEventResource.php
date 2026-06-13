<?php

namespace App\Filament\Resources\PesertaEvents;

use App\Filament\Resources\PesertaEvents\Pages\CreatePesertaEvent;
use App\Filament\Resources\PesertaEvents\Pages\EditPesertaEvent;
use App\Filament\Resources\PesertaEvents\Pages\ListPesertaEvents;
use App\Filament\Resources\PesertaEvents\Pages\ViewPesertaEvent;
use App\Filament\Resources\PesertaEvents\Schemas\PesertaEventForm;
use App\Filament\Resources\PesertaEvents\Schemas\PesertaEventInfolist;
use App\Filament\Resources\PesertaEvents\Tables\PesertaEventsTable;
use App\Models\PesertaEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PesertaEventResource extends Resource
{
    protected static ?string $model = PesertaEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserPlus;

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Schema $schema): Schema
    {
        return PesertaEventForm::configure($schema);
    }
    protected static string | UnitEnum | null $navigationGroup = 'Kegiatan & Pemberdayaan UMKM';
     protected static ?int $navigationSort = 13;

     

    public static function infolist(Schema $schema): Schema
    {
        return PesertaEventInfolist::configure($schema);
    }

      public static function getNavigationBadge(): ?string
{
    return static::getModel()::count();
}

    public static function table(Table $table): Table
    {
        return PesertaEventsTable::configure($table);
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
            'index' => ListPesertaEvents::route('/'),
            'create' => CreatePesertaEvent::route('/create'),
            'view' => ViewPesertaEvent::route('/{record}'),
            'edit' => EditPesertaEvent::route('/{record}/edit'),
        ];
    }
}
