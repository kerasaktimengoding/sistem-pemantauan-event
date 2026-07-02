<x-filament-panels::page>
    <!-- Custom Dashboard Header -->
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-2xl p-6 md:p-8 shadow-sm flex flex-col items-center text-center sm:text-left sm:flex-row sm:items-start gap-6 mb-2">
        <div class="p-4 bg-primary-50 dark:bg-primary-900/20 rounded-2xl flex-shrink-0">
            <x-filament::icon icon="heroicon-o-presentation-chart-bar" class="w-12 h-12 text-primary-600 dark:text-primary-400" />
        </div>
        <div>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-950 dark:text-white leading-tight mb-2">
                Sistem Pemantauan Terpadu
            </h1>
            <h2 class="text-lg md:text-xl font-semibold text-gray-700 dark:text-gray-300 mb-1">
                Dinas Koperasi Usaha Mikro Perindustrian dan Perdagangan
            </h2>
            <h3 class="text-sm md:text-base font-medium text-gray-500 dark:text-gray-400">
                Pemerintah Kabupaten Banjar &mdash; Portal Analisis Harga & Jadwal Kegiatan
            </h3>
        </div>
    </div>

    @if (method_exists($this, 'hasFiltersForm') && $this->hasFiltersForm())
        <div class="mb-6">
            {{ $this->filtersForm }}
        </div>
    @endif

    <div x-data="{ activeTab: 'agenda' }" class="space-y-6">
        <!-- Grid Navigation Styles (to ensure it works without npm run dev) -->
        <style>
            .dash-nav-grid {
                display: grid;
                grid-template-columns: repeat(1, minmax(0, 1fr));
                gap: 1rem;
                margin-bottom: 0.5rem;
                position: sticky;
                top: 0.5rem;
                z-index: 40;
            }
            @media (min-width: 768px) {
                .dash-nav-grid {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
            }
            .dash-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                padding: 1.25rem;
                border-radius: 1rem;
                border: 1px solid var(--event-border, #e2e8f0);
                background-color: rgba(255, 255, 255, 0.9);
                backdrop-filter: blur(12px);
                cursor: pointer;
                transition: all 0.2s ease-in-out;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            }
            .dark .dash-nav-item {
                border-color: rgba(71, 85, 105, 0.4);
                background-color: rgba(30, 41, 59, 0.9);
            }
            .dash-nav-item:hover {
                border-color: #93c5fd;
                background-color: #ffffff;
            }
            .dark .dash-nav-item:hover {
                border-color: #3b82f6;
                background-color: #1e293b;
            }
            .dash-nav-item.active {
                border-color: #3b82f6;
                background-color: #ffffff;
                box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.1), 0 4px 6px -4px rgba(59, 130, 246, 0.1);
                transform: scale(1.02);
            }
            .dark .dash-nav-item.active {
                background-color: #1e293b;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5);
            }
            .dash-icon-box {
                padding: 0.75rem;
                border-radius: 9999px;
                transition: all 0.2s;
            }
            /* Colors */
            .icon-agenda { background-color: #eff6ff; color: #3b82f6; }
            .dark .icon-agenda { background-color: rgba(59, 130, 246, 0.2); }
            .active .icon-agenda { background-color: #3b82f6; color: #ffffff; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3); }
            
            .icon-harga { background-color: #fef3c7; color: #f59e0b; }
            .dark .icon-harga { background-color: rgba(245, 158, 11, 0.2); }
            .active .icon-harga { background-color: #f59e0b; color: #ffffff; box-shadow: 0 4px 6px -1px rgba(245, 158, 11, 0.3); }

            .icon-wilayah { background-color: #ecfdf5; color: #10b981; }
            .dark .icon-wilayah { background-color: rgba(16, 185, 129, 0.2); }
            .active .icon-wilayah { background-color: #10b981; color: #ffffff; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3); }

            .dash-nav-title {
                font-weight: 700;
                font-size: 1rem;
                color: #111827;
                text-align: center;
                margin-bottom: 0.25rem;
            }
            .dark .dash-nav-title { color: #f9fafb; }
            .active .dash-nav-title { color: #2563eb; }
            .dark .active .dash-nav-title { color: #60a5fa; }

            .dash-nav-desc {
                font-size: 0.75rem;
                color: #6b7280;
                text-align: center;
                display: -webkit-box;
                -webkit-line-clamp: 1;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .dark .dash-nav-desc { color: #9ca3af; }
        </style>

        <div class="dash-nav-grid">
            <!-- 1. Agenda & Kegiatan -->
            <div 
                @click="activeTab = 'agenda'"
                :class="{'active': activeTab === 'agenda'}"
                class="dash-nav-item"
            >
                <div class="dash-icon-box icon-agenda">
                    <x-filament::icon icon="heroicon-o-calendar-days" class="w-6 h-6" />
                </div>
                <div>
                    <div class="dash-nav-title">Agenda & Kegiatan</div>
                    <div class="dash-nav-desc">Jadwal monitoring & kegiatan dinas</div>
                </div>
            </div>
            
            <!-- 2. Statistik & Tren Harga -->
            <div 
                @click="activeTab = 'harga'"
                :class="{'active': activeTab === 'harga'}"
                class="dash-nav-item"
            >
                <div class="dash-icon-box icon-harga">
                    <x-filament::icon icon="heroicon-o-presentation-chart-line" class="w-6 h-6" />
                </div>
                <div>
                    <div class="dash-nav-title">Statistik & Tren Harga</div>
                    <div class="dash-nav-desc">Analisis fluktuasi & data harga pasar</div>
                </div>
            </div>
            

        </div>

        <!-- TAB CONTENT AREA -->

        <!-- 1. Agenda & Kegiatan -->
        <div x-show="activeTab === 'agenda'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
            <div class="grid grid-cols-1 gap-6">
                @livewire(\App\Filament\Widgets\MyCalenderWidget::class)
                @livewire(\App\Filament\Widgets\JadwalMonitoringCalender::class)
            </div>
        </div>

        <!-- 2. Statistik & Tren Harga -->
        <div x-show="activeTab === 'harga'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" class="space-y-6">
            <!-- New custom combined widget -->
            @livewire(\App\Filament\Widgets\HargaPerDesaWidget::class)
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @livewire(\App\Filament\Widgets\InputHargaWidget::class)
                @livewire(\App\Filament\Widgets\InputHargaWidget2::class)
            </div>
        </div>


    </div>
</x-filament-panels::page>
