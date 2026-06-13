<?php

namespace App\Filament\Widgets;

use App\Models\EventKegiatan;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EventKegiatans\EventKegiatanResource;

class MyCalenderWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected static ?int $sort = 1;

    // protected int|string|array $columnSpan = '6';
    protected int|string|array $columnSpan = '12';



    protected function getEvents(FetchInfo $info): Builder
    {
        return EventKegiatan::query()
            ->whereDate('tanggal_selesai', '>=', $info->start)
            ->whereDate('tanggal_mulai', '<=', $info->end)
            ->where('status_event', 'Direncakan');

    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('create')
                ->label('Tambah Event Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(EventKegiatanResource::getUrl('create')),
        ];
    }
}
