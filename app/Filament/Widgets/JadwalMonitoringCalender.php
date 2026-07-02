<?php

namespace App\Filament\Widgets;

use App\Models\JadwalMonitoring;
use App\Models\Pasar;
use App\Models\Pegawai;
use Guava\Calendar\Enums\CalendarViewType;
use Guava\Calendar\Filament\CalendarWidget;
use Guava\Calendar\ValueObjects\FetchInfo;
use Guava\Calendar\ValueObjects\DateClickInfo;
use Illuminate\Database\Eloquent\Builder;

class JadwalMonitoringCalender extends CalendarWidget
{
    protected CalendarViewType $calendarView = CalendarViewType::DayGridMonth;
    protected static ?int $sort = 2; // Diletakkan di bawah MyCalenderWidget

    protected int|string|array $columnSpan = '12';

    protected string $view = 'filament.widgets.jadwal-monitoring-calender';
    protected bool $eventClickEnabled = true;
    protected bool $dateClickEnabled = true;

    // Filter properties
    public ?string $filterPasarId = null;
    public ?string $filterPegawaiId = null;
    public ?string $filterStatus = null;

    public function updatedFilterPasarId()
    {
        $this->refreshRecords();
    }

    public function updatedFilterPegawaiId()
    {
        $this->refreshRecords();
    }

    public function updatedFilterStatus()
    {
        $this->refreshRecords();
    }

    public function resetFilters()
    {
        $this->filterPasarId = null;
        $this->filterPegawaiId = null;
        $this->filterStatus = null;
        $this->refreshRecords();
    }

    public function getEventCounts(): array
    {
        $query = JadwalMonitoring::query();
        
        if ($this->filterPasarId) {
            $query->where('pasar_id', $this->filterPasarId);
        }
        
        if ($this->filterPegawaiId) {
            $query->where('pegawai_id', $this->filterPegawaiId);
        }

        return $query->selectRaw('status_monitoring, count(*) as count')
            ->groupBy('status_monitoring')
            ->pluck('count', 'status_monitoring')
            ->toArray();
    }

    protected function getEvents(FetchInfo $info): array | \Illuminate\Support\Collection | Builder
    {
        $query = JadwalMonitoring::query()
            ->with(['pasar', 'pegawai', 'kecamatan', 'desa'])
            ->whereDate('tanggal_rencana', '>=', $info->start)
            ->whereDate('tanggal_rencana', '<=', $info->end);

        if ($this->filterPasarId) {
            $query->where('pasar_id', $this->filterPasarId);
        }

        if ($this->filterPegawaiId) {
            $query->where('pegawai_id', $this->filterPegawaiId);
        }

        if ($this->filterStatus) {
            $query->where('status_monitoring', $this->filterStatus);
        }

        return $query;
    }

    public function getHeaderActions(): array
    {
        return [
            \Guava\Calendar\Filament\Actions\CreateAction::make('create')
                ->model(JadwalMonitoring::class)
                ->label('Tambah Jadwal Baru')
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
            'tanggal_rencana' => $info->date->format('Y-m-d'),
        ]);
    }

    public function getPasarOptions(): array
    {
        return Pasar::orderBy('nama_pasar')->pluck('nama_pasar', 'id')->toArray();
    }

    public function getPegawaiOptions(): array
    {
        return Pegawai::orderBy('nama_pegawai')->pluck('nama_pegawai', 'id')->toArray();
    }

    public function getUpcomingMonitorings(): \Illuminate\Support\Collection
    {
        return JadwalMonitoring::query()
            ->with(['pasar', 'pegawai'])
            ->whereDate('tanggal_rencana', '>=', now()->toDateString())
            ->where('status_monitoring', '!=', 'Selesai')
            ->where('status_monitoring', '!=', 'Batal')
            ->orderBy('tanggal_rencana', 'asc')
            ->limit(4)
            ->get();
    }
}
