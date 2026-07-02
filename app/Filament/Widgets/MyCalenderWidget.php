<?php

namespace App\Filament\Widgets;

use App\Models\EventKegiatan;
use App\Models\Wilayah;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Illuminate\Database\Eloquent\Builder;

class MyCalenderWidget extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = '12';

    protected string $view = 'filament.widgets.my-calendar-widget';
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;

    // Filters
    public ?string $filterWilayahId = null;
    public ?string $filterJenisEvent = null;
    public ?string $filterStatus = null;

    public function updatedFilterWilayahId()
    {
        $this->refreshRecords();
    }

    public function updatedFilterJenisEvent()
    {
        $this->refreshRecords();
    }

    public function updatedFilterStatus()
    {
        $this->refreshRecords();
    }

    public function resetFilters()
    {
        $this->filterWilayahId = null;
        $this->filterJenisEvent = null;
        $this->filterStatus = null;
        $this->refreshRecords();
    }

    public function getEventCounts(): array
    {
        $query = EventKegiatan::query();
        
        if ($this->filterWilayahId) {
            $query->where('wilayah_id', $this->filterWilayahId);
        }
        
        if ($this->filterJenisEvent) {
            $query->where('jenis_event', $this->filterJenisEvent);
        }

        return $query->selectRaw('status_event, count(*) as count')
            ->groupBy('status_event')
            ->pluck('count', 'status_event')
            ->toArray();
    }

    protected function getEvents(FetchInfo $info): array | \Illuminate\Support\Collection | Builder
    {
        $query = EventKegiatan::query()
            ->with(['wilayah.kecamatan', 'wilayah.desa'])
            ->whereDate('tanggal_selesai', '>=', $info->start)
            ->whereDate('tanggal_mulai', '<=', $info->end);

        if ($this->filterWilayahId) {
            $query->where('wilayah_id', $this->filterWilayahId);
        }

        if ($this->filterJenisEvent) {
            $query->where('jenis_event', $this->filterJenisEvent);
        }

        if ($this->filterStatus) {
            $query->where('status_event', $this->filterStatus);
        }

        return $query;
    }

    public function getHeaderActions(): array
    {
        return [
            \Guava\Calendar\Filament\Actions\CreateAction::make('create')
                ->model(EventKegiatan::class)
                ->label('Tambah Event Baru')
                ->icon('heroicon-o-plus-circle')
                ->color('primary'),
        ];
    }

    public function viewAction(): \Guava\Calendar\Filament\Actions\ViewAction
    {
        return \Guava\Calendar\Filament\Actions\ViewAction::make()
            ->extraModalFooterActions([
                $this->editAction(),
            ]);
    }

    public function editAction(): \Guava\Calendar\Filament\Actions\EditAction
    {
        return \Guava\Calendar\Filament\Actions\EditAction::make();
    }

    public function onDateClick(DateClickInfo $info): void
    {
        $this->mountAction('create', [
            'tanggal_mulai' => $info->date->format('Y-m-d H:i:s'),
            'tanggal_selesai' => $info->date->format('Y-m-d H:i:s'),
        ]);
    }

    public function getWilayahOptions(): array
    {
        return Wilayah::with(['kecamatan', 'desa'])
            ->get()
            ->mapWithKeys(function ($w) {
                $label = "Kec. " . ($w->kecamatan?->nama_kecamatan ?? '') . " - " . (($w->desa?->jenis === 'kelurahan' ? 'Kel. ' : 'Desa ') . ($w->desa?->nama_desa ?? ''));
                return [$w->id => $label];
            })
            ->toArray();
    }

    public function getJenisOptions(): array
    {
        return EventKegiatan::whereNotNull('jenis_event')
            ->distinct()
            ->orderBy('jenis_event')
            ->pluck('jenis_event', 'jenis_event')
            ->toArray();
    }

    public function getUpcomingEvents(): \Illuminate\Support\Collection
    {
        return EventKegiatan::query()
            ->with(['wilayah.kecamatan', 'wilayah.desa'])
            ->whereDate('tanggal_selesai', '>=', now()->toDateString())
            ->where('status_event', '!=', 'Selesai')
            ->where('status_event', '!=', 'Dibatalkan')
            ->orderBy('tanggal_mulai', 'asc')
            ->limit(4)
            ->get();
    }
}
