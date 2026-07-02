@php
    use Filament\Support\Facades\FilamentAsset;
    use Guava\Calendar\Enums\Context;
    use Filament\Support\Facades\FilamentColor;
    use Filament\Support\View\Components\ButtonComponent;
    use Carbon\Carbon;
@endphp

<x-filament-widgets::widget>
    <x-filament::section
        icon="heroicon-o-calendar"
        :after-header="$this->getCachedHeaderActionsComponent()"
        :footer="$this->getCachedFooterActionsComponent()"
    >
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Jadwal Monitoring Lapangan</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Kalender pemantauan harga pasar dan ketersediaan komoditas oleh petugas DKUMPP Kabupaten Banjar.
        </x-slot>

        <style>
            /* Base Theme Variables */
            :root {
                --monitoring-bg: #f8fafc;
                --monitoring-card-bg: #ffffff;
                --monitoring-border: #e2e8f0;
                --monitoring-text: #0f172a;
                --monitoring-text-secondary: #475569;
                --monitoring-badge-bg: #f1f5f9;
                --monitoring-select-border: #cbd5e1;
            }

            .dark {
                --monitoring-bg: rgba(30, 41, 59, 0.4);
                --monitoring-card-bg: #1e293b;
                --monitoring-border: rgba(71, 85, 105, 0.4);
                --monitoring-text: #f1f5f9;
                --monitoring-text-secondary: #94a3b8;
                --monitoring-badge-bg: #334155;
                --monitoring-select-border: #475569;
            }

            .ec-event.ec-preview,
            .ec-now-indicator {
                z-index: 30;
            }

            /* Responsive Main Grid */
            @media (min-width: 1024px) {
                .monitoring-main-grid {
                    grid-template-columns: 2fr 1fr !important;
                }
            }

            /* Dark mode override for calendar cell day numbers and day headers */
            .dark .fc-daygrid-day-number,
            .dark .fc-col-header-cell-cushion,
            .dark .ec-day-number,
            .dark .ec-day-header,
            .dark .ec-day,
            .dark .ec-day-grid-cell,
            .dark .ec-day-grid-day-number {
                color: #e5e7eb !important;
            }
        </style>

        <!-- FITUR FILTER ADVANCED -->
        <div class="monitoring-filter-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; padding: 1.25rem; background-color: var(--monitoring-bg); border: 1px solid var(--monitoring-border); border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="monitoring-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="monitoring-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--monitoring-text-secondary);">Pasar / Lokasi</label>
                <select wire:model.live="filterPasarId" class="monitoring-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--monitoring-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--monitoring-card-bg); color: var(--monitoring-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Pasar --</option>
                    @foreach($this->getPasarOptions() as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="monitoring-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="monitoring-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--monitoring-text-secondary);">Petugas Lapangan</label>
                <select wire:model.live="filterPegawaiId" class="monitoring-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--monitoring-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--monitoring-card-bg); color: var(--monitoring-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Petugas --</option>
                    @foreach($this->getPegawaiOptions() as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="monitoring-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="monitoring-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--monitoring-text-secondary);">Status</label>
                <select wire:model.live="filterStatus" class="monitoring-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--monitoring-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--monitoring-card-bg); color: var(--monitoring-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Status --</option>
                    <option value="Pending">Pending</option>
                    <option value="Proses">Proses</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Batal">Batal</option>
                </select>
            </div>

            <div class="monitoring-reset-container" style="display: flex; align-items: flex-end;">
                @if($filterPasarId || $filterPegawaiId || $filterStatus)
                    <button 
                        type="button"
                        wire:click="resetFilters" 
                        class="monitoring-reset-btn"
                        style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; color: #dc2626; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;"
                    >
                        <x-filament::icon icon="heroicon-m-x-mark" class="w-4 h-4" />
                        Reset Filter
                    </button>
                @else
                    <div class="monitoring-filter-placeholder" style="width: 100%; font-size: 0.75rem; color: var(--monitoring-text-secondary); font-style: italic; padding-bottom: 0.5rem; text-align: left;">
                        * Gunakan opsi di atas untuk menyaring jadwal
                    </div>
                @endif
            </div>
        </div>

        <!-- KALENDER UTAMA -->
        <div
            wire:ignore
            x-load
            x-load-src="{{ FilamentAsset::getAlpineComponentSrc('calendar', 'guava/calendar') }}"
            x-data="calendar({
                view: @js($this->getCalendarView()),
                locale: @js($this->getLocale()),
                firstDay: @js($this->getFirstDay()),
                dayMaxEvents: @js($this->getDayMaxEvents()),
                eventContent: @js($this->getEventContentJs()),
                eventClickEnabled: @js($this->isEventClickEnabled()),
                eventDragEnabled: @js($this->isEventDragEnabled()),
                eventResizeEnabled: @js($this->isEventResizeEnabled()),
                noEventsClickEnabled: @js($this->isNoEventsClickEnabled()),
                dateClickEnabled: @js($this->isDateClickEnabled()),
                dateSelectEnabled: @js($this->isDateSelectEnabled()),
                datesSetEnabled: @js($this->isDatesSetEnabled()),
                viewDidMountEnabled: @js($this->isViewDidMountEnabled()),
                eventAllUpdatedEnabled: @js($this->isEventAllUpdatedEnabled()),
                hasDateClickContextMenu: @js($this->hasContextMenu(Context::DateClick)),
                hasDateSelectContextMenu: @js($this->hasContextMenu(Context::DateSelect)),
                hasEventClickContextMenu: @js($this->hasContextMenu(Context::EventClick)),
                hasNoEventsClickContextMenu: @js($this->hasContextMenu(Context::NoEventsClick)),
                resources: @js($this->getResourcesJs()),
                resourceLabelContent: @js($this->getResourceLabelContentJs()),
                theme: @js($this->getTheme()),
                options: @js($this->getOptions()),
                eventAssetUrl: @js(FilamentAsset::getAlpineComponentSrc('calendar-event', 'guava/calendar')),
            })"
            @class(FilamentColor::getComponentClasses(ButtonComponent::class, 'primary'))
        >
            <div data-calendar></div>
            @if($this->hasContextMenu())
                <x-guava-calendar::context-menu/>
            @endif
        </div>

        <!-- SUMMARY, STATS GRID & SIDEBAR -->
        <div class="monitoring-main-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 2rem;">
            
            <!-- SEKSI COUNTER LEGEND -->
            <div class="monitoring-stats-section" style="padding: 1.25rem; background-color: var(--monitoring-bg); border: 1px solid var(--monitoring-border); border-radius: 0.75rem;">
                <div class="monitoring-section-title" style="font-size: 0.875rem; font-weight: 700; color: var(--monitoring-text); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" style="width: 0.6rem; height: 0.6rem; border-radius: 50%; display: inline-block; background-color: #10b981;"></span>
                    Statistik & Keterangan Status Monitoring
                </div>
                
                @php
                    $counts = $this->getEventCounts();
                    $statuses = [
                        'Pending' => ['color' => '#f59e0b', 'bg' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800/50', 'desc' => 'Direncanakan namun belum mulai berjalan.'],
                        'Proses' => ['color' => '#3b82f6', 'bg' => 'bg-blue-500', 'text' => 'text-blue-700 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800/50', 'desc' => 'Petugas sedang melakukan pemantauan harga.'],
                        'Selesai' => ['color' => '#10b981', 'bg' => 'bg-green-500', 'text' => 'text-green-700 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800/50', 'desc' => 'Selesai dipantau dan laporan diinput.'],
                        'Batal' => ['color' => '#ef4444', 'bg' => 'bg-red-500', 'text' => 'text-red-700 dark:text-red-300', 'border' => 'border-red-200 dark:border-red-800/50', 'desc' => 'Jadwal dibatalkan karena kendala teknis.'],
                    ];
                @endphp

                <div class="monitoring-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    @foreach($statuses as $label => $theme)
                        <div class="monitoring-stats-card" style="padding: 1rem; background-color: var(--monitoring-card-bg); border: 1px solid var(--monitoring-border); border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; flex-direction: column; justify-content: space-between; min-height: 95px;">
                            <div class="monitoring-stats-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                                <div class="monitoring-stats-status" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.875rem;">
                                    <span class="monitoring-stats-dot" style="width: 0.6rem; height: 0.6rem; border-radius: 50%; display: inline-block; background-color: {{ $theme['color'] }}"></span>
                                    <span class="{{ $theme['text'] }}" style="color: var(--monitoring-text);">{{ $label }}</span>
                                </div>
                                <span class="monitoring-stats-count" style="font-size: 0.75rem; font-weight: 800; padding: 0.125rem 0.5rem; background-color: var(--monitoring-badge-bg); color: var(--monitoring-text); border-radius: 9999px;">
                                    {{ $counts[$label] ?? 0 }}
                                </span>
                            </div>
                            <div class="monitoring-stats-desc" style="font-size: 0.75rem; color: var(--monitoring-text-secondary); line-height: 1.35;">
                                {{ $theme['desc'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SEKSI JADWAL UPCOMING -->
            <div class="monitoring-upcoming-section" style="padding: 1.25rem; background-color: var(--monitoring-bg); border: 1px solid var(--monitoring-border); border-radius: 0.75rem; display: flex; flex-direction: column;">
                <div class="monitoring-section-title" style="font-size: 0.875rem; font-weight: 700; color: var(--monitoring-text); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4 text-emerald-500" />
                    Jadwal Terdekat (Mendatang)
                </div>

                @php
                    $upcoming = $this->getUpcomingMonitorings();
                @endphp

                <div class="monitoring-upcoming-list" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; flex: 1;">
                    @forelse($upcoming as $item)
                        @php
                            $diff = Carbon::parse($item->tanggal_rencana)->diffForHumans();
                            $statusTheme = match($item->status_monitoring) {
                                'Pending' => 'background-color: rgba(245, 158, 11, 0.15); color: #d97706;',
                                'Proses' => 'background-color: rgba(59, 130, 246, 0.15); color: #2563eb;',
                                'Selesai' => 'background-color: rgba(16, 185, 129, 0.15); color: #059669;',
                                default => 'background-color: rgba(107, 114, 128, 0.15); color: #4b5563;',
                            };
                        @endphp
                        <div class="monitoring-upcoming-item" style="padding: 0.75rem; background-color: var(--monitoring-card-bg); border: 1px solid var(--monitoring-border); border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; gap: 0.75rem; align-items: flex-start; transition: border-color 0.2s;">
                            <div class="monitoring-upcoming-datebox dark:bg-teal-950/40 dark:text-teal-400" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.5rem; background-color: #f0fdfa; color: #0f766e; border-radius: 0.375rem; font-weight: 600; text-align: center; min-width: 3.25rem;">
                                <span class="monitoring-upcoming-month" style="font-size: 0.65rem; text-transform: uppercase;">{{ Carbon::parse($item->tanggal_rencana)->translatedFormat('M') }}</span>
                                <span class="monitoring-upcoming-day" style="font-size: 1.125rem; font-weight: 900; line-height: 1;">{{ Carbon::parse($item->tanggal_rencana)->format('d') }}</span>
                            </div>
                            <div class="monitoring-upcoming-info" style="flex: 1; min-width: 0;">
                                <div class="monitoring-upcoming-meta" style="display: flex; align-items: center; justify-content: space-between; gap: 0.25rem; margin-bottom: 0.25rem;">
                                    <span class="monitoring-upcoming-diff" style="font-size: 0.75rem; font-weight: 700; color: var(--monitoring-text-secondary);">{{ $diff }}</span>
                                    <span class="monitoring-upcoming-badge" style="padding: 0.125rem 0.375rem; font-size: 0.625rem; font-weight: 700; border-radius: 0.25rem; {{ $statusTheme }}">
                                        {{ $item->status_monitoring }}
                                    </span>
                                </div>
                                <h4 class="monitoring-upcoming-title" style="font-size: 0.875rem; font-weight: 700; color: var(--monitoring-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0;">
                                    {{ $item->pasar?->nama_pasar ?? 'Lokasi tidak diset' }}
                                </h4>
                                <div class="monitoring-upcoming-officer" style="font-size: 0.75rem; color: var(--monitoring-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.125rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <x-filament::icon icon="heroicon-m-user" class="w-3.5 h-3.5 text-gray-400 inline" />
                                    <span style="color: var(--monitoring-text-secondary);">{{ $item->pegawai?->nama_pegawai ?? 'Belum ada petugas' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="monitoring-upcoming-empty" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; background-color: var(--monitoring-card-bg); border: 1px dashed var(--monitoring-border); border-radius: 0.5rem; text-align: center; flex: 1;">
                            <x-filament::icon icon="heroicon-o-calendar-days" class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" />
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500" style="color: var(--monitoring-text-secondary);">Tidak ada jadwal terdekat</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </x-filament::section>
    <x-filament-actions::modals/>
</x-filament-widgets::widget>
