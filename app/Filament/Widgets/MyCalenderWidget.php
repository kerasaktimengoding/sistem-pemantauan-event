<?php

namespace App\Filament\Widgets;

use App\Models\EventKegiatan;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EventKegiatans\EventKegiatanResource;
use Filament\Actions\Action;

class MyCalenderWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected static ?int $sort = 1;

    // protected int|string|array $columnSpan = '6';
    protected int|string|array $columnSpan = '12';

    protected string $view = 'filament.widgets.my-calendar-widget';
    protected bool $eventClickEnabled = true;

    public function getEventCounts(): array
    {
        return EventKegiatan::selectRaw('status_event, count(*) as count')
            ->groupBy('status_event')
            ->pluck('count', 'status_event')
            ->toArray();
    }
    protected function getEvents(FetchInfo $info): array | \Illuminate\Support\Collection | Builder
    {
        return EventKegiatan::query()
            ->whereDate('tanggal_selesai', '>=', $info->start)
            ->whereDate('tanggal_mulai', '<=', $info->end);
    }

   

    public function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Tambah Event Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->url(EventKegiatanResource::getUrl('create')),
        ];
    }

    public function viewAction(): \Guava\Calendar\Filament\Actions\ViewAction
    {
        return \Guava\Calendar\Filament\Actions\ViewAction::make()
            ->extraModalFooterActions([
                $this->editAction(),
            ]);
    }

    public function createAction(string $model, string|null $name = null): \Guava\Calendar\Filament\Actions\CreateAction
    {
        return \Guava\Calendar\Filament\Actions\CreateAction::make()
            ->model($model)
            ->name($name);
    }

    

    public function editAction(): \Guava\Calendar\Filament\Actions\EditAction
    {
        return \Guava\Calendar\Filament\Actions\EditAction::make();
    }

    public function monthChanged(string $month): void
    {
        // TODO: Implement method
        dd($month);
    }

    public function weekChanged(string $week): void
    {
        // TODO: Implement method
        dd($week);
    }
}
