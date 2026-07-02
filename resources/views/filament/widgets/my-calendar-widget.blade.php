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
                <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Kalender Event & Kegiatan</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Agenda kegiatan, sosialisasi, bazaar, dan edukasi pasar di lingkungan DKUMPP Kabupaten Banjar.
        </x-slot>

        <style>
            /* Base Theme Variables */
            :root {
                --event-bg: #f8fafc;
                --event-card-bg: #ffffff;
                --event-border: #e2e8f0;
                --event-text: #0f172a;
                --event-text-secondary: #475569;
                --event-badge-bg: #f1f5f9;
                --event-select-border: #cbd5e1;
            }

            .dark {
                --event-bg: rgba(30, 41, 59, 0.4);
                --event-card-bg: #1e293b;
                --event-border: rgba(71, 85, 105, 0.4);
                --event-text: #f1f5f9;
                --event-text-secondary: #94a3b8;
                --event-badge-bg: #334155;
                --event-select-border: #475569;
            }

            .ec-event.ec-preview,
            .ec-now-indicator {
                z-index: 30;
            }

            /* Responsive Main Grid */
            @media (min-width: 1024px) {
                .event-main-grid {
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
        <div class="event-filter-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; padding: 1.25rem; background-color: var(--event-bg); border: 1px solid var(--event-border); border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
            <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Zonasi Wilayah</label>
                <select wire:model.live="filterWilayahId" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Wilayah --</option>
                    @foreach($this->getWilayahOptions() as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Jenis Event</label>
                <select wire:model.live="filterJenisEvent" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Jenis --</option>
                    @foreach($this->getJenisOptions() as $jenis)
                        <option value="{{ $jenis }}">{{ $jenis }}</option>
                    @endforeach
                </select>
            </div>

            <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Status Event</label>
                <select wire:model.live="filterStatus" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                    <option value="">-- Semua Status --</option>
                    <option value="Direncanakan">Direncanakan</option>
                    <option value="Berjalan">Berjalan</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>

            <div class="event-reset-container" style="display: flex; align-items: flex-end;">
                @if($filterWilayahId || $filterJenisEvent || $filterStatus)
                    <button 
                        type="button"
                        wire:click="resetFilters" 
                        class="event-reset-btn"
                        style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 0.5rem; padding: 0.5rem 1rem; font-size: 0.875rem; font-weight: 600; color: #dc2626; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 0.5rem; cursor: pointer; transition: background-color 0.2s, border-color 0.2s;"
                    >
                        <x-filament::icon icon="heroicon-m-x-mark" class="w-4 h-4" />
                        Reset Filter
                    </button>
                @else
                    <div class="event-filter-placeholder" style="width: 100%; font-size: 0.75rem; color: var(--event-text-secondary); font-style: italic; padding-bottom: 0.5rem; text-align: left;">
                        * Gunakan opsi di atas untuk menyaring agenda
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
        <div class="event-main-grid" style="display: grid; grid-template-columns: 1fr; gap: 1.5rem; margin-top: 2rem;">
            
            <!-- SEKSI COUNTER LEGEND -->
            <div class="event-stats-section" style="padding: 1.25rem; background-color: var(--event-bg); border: 1px solid var(--event-border); border-radius: 0.75rem;">
                <div class="event-section-title" style="font-size: 0.875rem; font-weight: 700; color: var(--event-text); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse" style="width: 0.6rem; height: 0.6rem; border-radius: 50%; display: inline-block; background-color: #0ea5e9;"></span>
                    Keterangan Status & Statistik Kegiatan
                </div>
                
                @php
                    $counts = $this->getEventCounts();
                    $statuses = [
                        'Direncanakan' => ['color' => '#3b82f6', 'bg' => 'bg-blue-500', 'text' => 'text-blue-700 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800/50', 'desc' => 'Kegiatan terjadwal yang sedang dipersiapkan.'],
                        'Berjalan' => ['color' => '#f59e0b', 'bg' => 'bg-amber-500', 'text' => 'text-amber-700 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800/50', 'desc' => 'Kegiatan sedang berlangsung di lapangan saat ini.'],
                        'Selesai' => ['color' => '#10b981', 'bg' => 'bg-green-500', 'text' => 'text-green-700 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800/50', 'desc' => 'Kegiatan telah rampung dilaksanakan.'],
                        'Dibatalkan' => ['color' => '#ef4444', 'bg' => 'bg-red-500', 'text' => 'text-red-700 dark:text-red-300', 'border' => 'border-red-200 dark:border-red-800/50', 'desc' => 'Kegiatan dibatalkan atau ditunda.'],
                    ];
                @endphp

                <div class="event-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    @foreach($statuses as $label => $theme)
                        <div class="event-stats-card" style="padding: 1rem; background-color: var(--event-card-bg); border: 1px solid var(--event-border); border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; flex-direction: column; justify-content: space-between; min-height: 95px;">
                            <div class="event-stats-header" style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem;">
                                <div class="event-stats-status" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.875rem;">
                                    <span class="event-stats-dot" style="width: 0.6rem; height: 0.6rem; border-radius: 50%; display: inline-block; background-color: {{ $theme['color'] }}"></span>
                                    <span class="{{ $theme['text'] }}" style="color: var(--event-text);">{{ $label }}</span>
                                </div>
                                <span class="event-stats-count" style="font-size: 0.75rem; font-weight: 800; padding: 0.125rem 0.5rem; background-color: var(--event-badge-bg); color: var(--event-text); border-radius: 9999px;">
                                    {{ $counts[$label] ?? 0 }}
                                </span>
                            </div>
                            <div class="event-stats-desc" style="font-size: 0.75rem; color: var(--event-text-secondary); line-height: 1.35;">
                                {{ $theme['desc'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- SEKSI JADWAL UPCOMING -->
            <div class="event-upcoming-section" style="padding: 1.25rem; background-color: var(--event-bg); border: 1px solid var(--event-border); border-radius: 0.75rem; display: flex; flex-direction: column;">
                <div class="event-section-title" style="font-size: 0.875rem; font-weight: 700; color: var(--event-text); display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem;">
                    <x-filament::icon icon="heroicon-o-sparkles" class="w-4 h-4 text-sky-500" />
                    Event Terdekat (Mendatang)
                </div>

                @php
                    $upcoming = $this->getUpcomingEvents();
                @endphp

                <div class="event-upcoming-list" style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem; flex: 1;">
                    @forelse($upcoming as $item)
                        @php
                            $diff = Carbon::parse($item->tanggal_mulai)->diffForHumans();
                            $statusTheme = match($item->status_event) {
                                'Direncanakan' => 'background-color: rgba(59, 130, 246, 0.15); color: #2563eb;',
                                'Berjalan' => 'background-color: rgba(245, 158, 11, 0.15); color: #d97706;',
                                'Selesai' => 'background-color: rgba(16, 185, 129, 0.15); color: #059669;',
                                default => 'background-color: rgba(107, 114, 128, 0.15); color: #4b5563;',
                            };
                            $wilayahText = "Kec. " . ($item->wilayah?->kecamatan?->nama_kecamatan ?? '') . " - " . ($item->wilayah?->desa?->nama_desa ?? '');
                        @endphp
                        <div class="event-upcoming-item" style="padding: 0.75rem; background-color: var(--event-card-bg); border: 1px solid var(--event-border); border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); display: flex; gap: 0.75rem; align-items: flex-start; transition: border-color 0.2s;">
                            <div class="event-upcoming-datebox dark:bg-sky-950/40 dark:text-sky-400" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 0.5rem; background-color: #f0f9ff; color: #0369a1; border-radius: 0.375rem; font-weight: 600; text-align: center; min-width: 3.25rem;">
                                <span class="event-upcoming-month" style="font-size: 0.65rem; text-transform: uppercase;">{{ Carbon::parse($item->tanggal_mulai)->translatedFormat('M') }}</span>
                                <span class="event-upcoming-day" style="font-size: 1.125rem; font-weight: 900; line-height: 1;">{{ Carbon::parse($item->tanggal_mulai)->format('d') }}</span>
                            </div>
                            <div class="event-upcoming-info" style="flex: 1; min-width: 0;">
                                <div class="event-upcoming-meta" style="display: flex; align-items: center; justify-content: space-between; gap: 0.25rem; margin-bottom: 0.25rem;">
                                    <span class="event-upcoming-diff" style="font-size: 0.75rem; font-weight: 700; color: var(--event-text-secondary);">{{ $diff }}</span>
                                    <span class="event-upcoming-badge" style="padding: 0.125rem 0.375rem; font-size: 0.625rem; font-weight: 700; border-radius: 0.25rem; {{ $statusTheme }}">
                                        {{ $item->status_event }}
                                    </span>
                                </div>
                                <h4 class="event-upcoming-title" style="font-size: 0.875rem; font-weight: 700; color: var(--event-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0;">
                                    {{ $item->nama_event }}
                                </h4>
                                <div class="event-upcoming-location" style="font-size: 0.75rem; color: var(--event-text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 0.125rem; display: flex; align-items: center; gap: 0.25rem;">
                                    <x-filament::icon icon="heroicon-m-map-pin" class="w-3.5 h-3.5 text-gray-400 inline" />
                                    <span style="color: var(--event-text-secondary);">{{ $item->lokasi_event }} ({{ $wilayahText }})</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="event-upcoming-empty" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem; background-color: var(--event-card-bg); border: 1px dashed var(--event-border); border-radius: 0.5rem; text-align: center; flex: 1;">
                            <x-filament::icon icon="heroicon-o-calendar-days" class="w-8 h-8 text-gray-300 dark:text-gray-600 mb-2" />
                            <span class="text-xs font-semibold text-gray-400 dark:text-gray-500" style="color: var(--event-text-secondary);">Tidak ada event terdekat</span>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </x-filament::section>
    <x-filament-actions::modals/>
</x-filament-widgets::widget>
