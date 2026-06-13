<?php

namespace App\Filament\Resources\JadwalMonitorings;

use App\Filament\Resources\JadwalMonitorings\Pages\CreateJadwalMonitoring;
use App\Filament\Resources\JadwalMonitorings\Pages\EditJadwalMonitoring;
use App\Filament\Resources\JadwalMonitorings\Pages\ListJadwalMonitorings;
use App\Filament\Resources\JadwalMonitorings\Pages\ViewJadwalMonitoring;
use App\Filament\Resources\JadwalMonitorings\Schemas\JadwalMonitoringForm;
use App\Filament\Resources\JadwalMonitorings\Schemas\JadwalMonitoringInfolist;
use App\Filament\Resources\JadwalMonitorings\Tables\JadwalMonitoringsTable;
use App\Models\JadwalMonitoring;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;


class JadwalMonitoringResource extends Resource
{
    protected static ?string $model = JadwalMonitoring::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::RectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Aktivitas Pemantauan Harga';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $navigationLabel = 'Jadwal Monitoring';
    protected static ?string $pluralModelLabel = 'Jadwal Monitoring';
     protected static ?int $navigationSort = 10;
    public static function form(Schema $schema): Schema
    {
        return JadwalMonitoringForm::configure($schema);
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function infolist(Schema $schema): Schema
    {
        return JadwalMonitoringInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JadwalMonitoringsTable::configure($table);
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
            'index' => ListJadwalMonitorings::route('/'),
            'create' => CreateJadwalMonitoring::route('/create'),
            'view' => ViewJadwalMonitoring::route('/{record}'),
            'edit' => EditJadwalMonitoring::route('/{record}/edit'),
        ];
    }
}
