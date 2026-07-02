<x-filament-widgets::widget>
    <x-filament::section
        icon="heroicon-o-presentation-chart-line"
    >
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <span class="text-xl font-bold tracking-tight text-gray-950 dark:text-white">Analisis Fluktuasi & Distribusi Harga</span>
                <button onclick="window.print()" class="print-hidden flex items-center gap-2 bg-primary-600 hover:bg-primary-500 text-white font-medium text-sm px-4 py-2 rounded-lg transition-colors duration-200">
                    <x-filament::icon icon="heroicon-o-printer" class="w-4 h-4" />
                    Cetak Laporan
                </button>
            </div>
        </x-slot>

        <x-slot name="description">
            Statistik tren perbandingan harga (minimum, rata-rata, maksimum) dan sebaran harga komoditas per wilayah desa/pasar.
        </x-slot>

        <!-- Include Chart.js and custom styles inside the single root element -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            /* Base Theme Variables from Agenda Widget */
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

            /* Hide print-only elements on screen */
            .hpd-widget-container .print-only {
                display: none !important;
            }
            .hpd-widget-container .print-sig-block {
                display: none !important;
            }
            
            /* Screen table styles */
            .hpd-table {
                border-collapse: collapse !important;
                width: 100% !important;
            }
            .hpd-table th, .hpd-table td {
                border: 1px solid var(--event-border) !important;
                padding: 10px 16px !important;
            }

            /* Print styles */
            @media print {
                body {
                    background-color: white !important;
                    color: black !important;
                }
                /* Hide non-print areas */
                .print-hidden,
                .no-print,
                header,
                footer,
                nav,
                aside,
                .fi-sidebar,
                .fi-topbar,
                .fi-breadcrumbs,
                .fi-header,
                .fi-panel-disable-blade-icon-components,
                button {
                    display: none !important;
                }

                .fi-section {
                    box-shadow: none !important;
                    background: transparent !important;
                    border: none !important;
                }

                /* Container full page */
                .hpd-widget-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 10px !important;
                    margin: 0 !important;
                    background-color: white !important;
                    color: black !important;
                    box-shadow: none !important;
                    border: none !important;
                    position: absolute !important;
                    left: 0 !important;
                    top: 0 !important;
                }

                /* Show print elements */
                .hpd-widget-container .print-only {
                    display: block !important;
                }

                .hpd-widget-container .print-sig-block {
                    display: flex !important;
                    justify-content: space-between !important;
                    margin-top: 50px !important;
                    page-break-inside: avoid !important;
                }

                .hpd-widget-container .hpd-table th,
                .hpd-widget-container .hpd-table td {
                    border: 1px solid #000000 !important;
                    color: black !important;
                    padding: 6px 10px !important;
                }
            }
        </style>

        <div class="hpd-widget-container">
            <!-- ================= PRINT LETTERHEAD (KOP SURAT) ================= -->
            <div class="print-only mb-6 pb-4 border-b-4 border-double border-black">
                <div class="flex items-center justify-center gap-6">
                    <img src="https://www.banjarkab.go.id/assets/images/logo.png" class="h-20 w-auto" alt="Logo Banjar">
                    <div class="text-center">
                        <h2 class="text-lg font-bold uppercase tracking-wider text-black">PEMERINTAH KABUPATEN BANJAR</h2>
                        <h1 class="text-xl font-extrabold uppercase text-black leading-tight">DINAS KOPERASI USAHA MIKRO PERINDUSTRIAN DAN PERDAGANGAN</h1>
                        <p class="text-xs text-gray-800 italic mt-1">Jl. Pangeran Hidayatullah No. 24, Martapura, Kalimantan Selatan, Kode Pos 70614</p>
                        <p class="text-xs text-gray-800">Telp: (0511) 4721234 | Email: dkumpp@banjarkab.go.id | Web: dkumpp.banjarkab.go.id</p>
                    </div>
                </div>
            </div>

            <!-- ================= PRINT DOCUMENT TITLE ================= -->
            <div class="print-only text-center mb-6">
                <h3 class="text-base font-bold uppercase border-b border-black pb-1 inline-block text-black">
                    LAPORAN ANALISIS PERKEMBANGAN & PERBANDINGAN HARGA BAHAN POKOK
                </h3>
                <p class="text-xs text-gray-800 mt-2">
                    Tahun Laporan: <strong class="text-black">{{ $filterTahun }}</strong> | Tipe Analisis: 
                    <strong class="text-black">
                        @if($analysisType === 'tunggal')
                            Tren Tunggal Komoditas ({{ \App\Models\Komoditas::find($filterKomoditasId)?->nama_komoditas }})
                        @elseif($analysisType === 'komoditas')
                            Perbandingan Beberapa Komoditas
                        @elseif($analysisType === 'wilayah')
                            Perbandingan Harga Kelurahan/Desa ({{ \App\Models\Komoditas::find($filterKomoditasId)?->nama_komoditas }})
                        @elseif($analysisType === 'pasar')
                            Perbandingan Harga Pasar ({{ \App\Models\Komoditas::find($filterKomoditasId)?->nama_komoditas }})
                        @endif
                    </strong>
                </p>
            </div>

            <!-- ================= FILTERS FORM ================= -->
            <div class="print-hidden event-filter-container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1.25rem; padding: 1.25rem; background-color: var(--event-bg); border: 1px solid var(--event-border); border-radius: 0.75rem; margin-bottom: 1.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <!-- 1. Tipe Analisis -->
                <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Tipe Analisis</label>
                    <select wire:model.live="analysisType" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                        <option value="tunggal">📈 Tren Tunggal Komoditas</option>
                        <option value="komoditas">📊 Perbandingan Komoditas</option>
                        <option value="wilayah">🏡 Perbandingan Kelurahan/Desa</option>
                        <option value="pasar">🛒 Perbandingan Pasar</option>
                    </select>
                </div>
                
                <!-- 2. Tahun -->
                <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                    <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Tahun</label>
                    <select wire:model.live="filterTahun" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                        @foreach($this->getTahunOptions() as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Conditional filters based on Tipe Analisis -->
                @if($analysisType === 'tunggal' || $analysisType === 'komoditas')
                    <!-- Tipe Wilayah -->
                    <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Tipe Wilayah</label>
                        <select wire:model.live="locationType" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                            <option value="desa">Kelurahan / Desa</option>
                            <option value="pasar">Pasar Tradisional</option>
                        </select>
                    </div>

                    <!-- Pilihan Desa / Pasar -->
                    @if($locationType === 'desa')
                        <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                            <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Kelurahan / Desa</label>
                            <select wire:model.live="filterDesaId" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                                @foreach($this->getDesaOptions() as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @else
                        <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                            <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Pasar</label>
                            <select wire:model.live="filterPasarId" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                                @foreach($this->getPasarOptions() as $id => $nama)
                                    <option value="{{ $id }}">{{ $nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endif

                @if($analysisType === 'tunggal' || $analysisType === 'wilayah' || $analysisType === 'pasar')
                    <!-- Komoditas Utama -->
                    <div class="event-filter-group" style="display: flex; flex-direction: column; gap: 0.35rem;">
                        <label class="event-filter-label" style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: var(--event-text-secondary);">Komoditas</label>
                        <select wire:model.live="filterKomoditasId" class="event-filter-select" style="border-radius: 0.5rem; border: 1px solid var(--event-select-border); font-size: 0.875rem; padding: 0.5rem 0.75rem; background-color: var(--event-card-bg); color: var(--event-text); width: 100%; outline: none; transition: border-color 0.2s;">
                            @foreach($this->getKomoditasOptions() as $id => $nama)
                                <option value="{{ $id }}">{{ $nama }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="event-reset-container" style="display: flex; align-items: flex-end; justify-content: flex-end; width: 100%;">
                    <div class="event-filter-placeholder" style="font-size: 0.75rem; color: var(--event-text-secondary); font-style: italic; padding-bottom: 0.5rem;">
                        * Gunakan opsi di atas untuk parameter data
                    </div>
                </div>
            </div>

        <!-- ================= DYNAMIC MULTI-SELECT CHECKBOX PANELS ================= -->
        @if($analysisType === 'komoditas')
            <div class="print-hidden p-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl mb-6">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <x-filament::icon icon="heroicon-o-funnel" class="w-4.5 h-4.5 text-gray-400" />
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Pilih Pembanding (Multi-select)</h4>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 max-h-32 overflow-y-auto">
                    @foreach($this->getKomoditasOptions() as $id => $nama)
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                            <input type="checkbox" wire:model.live="selectedKomoditasIds" value="{{ $id }}" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @elseif($analysisType === 'wilayah')
            <div class="print-hidden p-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl mb-6">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <x-filament::icon icon="heroicon-o-funnel" class="w-4.5 h-4.5 text-gray-400" />
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Pilih Kelurahan/Desa Pembanding (Multi-select)</h4>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-3 max-h-32 overflow-y-auto">
                    @foreach($this->getActiveDesaOptions() as $id => $nama)
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                            <input type="checkbox" wire:model.live="selectedDesaIds" value="{{ $id }}" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @elseif($analysisType === 'pasar')
            <div class="print-hidden p-5 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl mb-6">
                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-gray-100 dark:border-gray-800">
                    <x-filament::icon icon="heroicon-o-funnel" class="w-4.5 h-4.5 text-gray-400" />
                    <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Pilih Pasar Pembanding (Multi-select)</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 max-h-32 overflow-y-auto">
                    @foreach($this->getActivePasarOptions() as $id => $nama)
                        <label class="flex items-center gap-3 p-2 hover:bg-gray-50 dark:hover:bg-gray-800/50 rounded-lg cursor-pointer transition-colors border border-transparent hover:border-gray-200 dark:hover:border-gray-700">
                            <input type="checkbox" wire:model.live="selectedPasarIds" value="{{ $id }}" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $nama }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- ================= CHARTS VIEWPORT ================= -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 print:grid-cols-3 print:gap-4 print:mb-6"
             x-data="{
                lineLabels: @entangle('lineLabels'),
                chartDatasets: @entangle('chartDatasets'),
                doughnutLabels: @entangle('doughnutLabels'),
                doughnutValues: @entangle('doughnutValues'),
                lineChart: null,
                doughnutChart: null,
                init() {
                    this.$nextTick(() => {
                        this.renderCharts();
                    });
                    this.$watch('chartDatasets', () => this.renderCharts());
                    this.$watch('lineLabels', () => this.renderCharts());
                    this.$watch('doughnutLabels', () => this.renderCharts());
                    this.$watch('doughnutValues', () => this.renderCharts());
                },
                renderCharts() {
                    // Clean existing instances
                    if (this.lineChart) this.lineChart.destroy();
                    if (this.doughnutChart) this.doughnutChart.destroy();

                    const isDark = document.documentElement.classList.contains('dark');
                    const gridColor = isDark ? 'rgba(148, 163, 184, 0.1)' : 'rgba(226, 232, 240, 0.6)';
                    const labelColor = isDark ? '#94a3b8' : '#475569';

                    // 1. Line Chart rendering
                    const ctxLine = this.$refs.lineCanvas.getContext('2d');
                    this.lineChart = new Chart(ctxLine, {
                        type: 'line',
                        data: {
                            labels: this.lineLabels,
                            datasets: this.chartDatasets
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                    labels: { color: labelColor, font: { weight: 'bold', size: 11 } }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { color: gridColor },
                                    ticks: { color: labelColor }
                                },
                                y: {
                                    grid: { color: gridColor },
                                    ticks: { 
                                        color: labelColor,
                                        callback: function(value) {
                                            return 'Rp ' + value.toLocaleString('id-ID');
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // 2. Doughnut Chart rendering
                    if (this.doughnutLabels && this.doughnutLabels.length > 0) {
                        const ctxDoughnut = this.$refs.doughnutCanvas.getContext('2d');
                        this.doughnutChart = new Chart(ctxDoughnut, {
                            type: 'doughnut',
                            data: {
                                labels: this.doughnutLabels,
                                datasets: [{
                                    data: this.doughnutValues,
                                    backgroundColor: [
                                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6',
                                        '#06b6d4', '#ec4899', '#14b8a6', '#f43f5e', '#6366f1'
                                    ],
                                    borderWidth: 2,
                                    borderColor: isDark ? '#1e293b' : '#ffffff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: { color: labelColor, font: { size: 10 } }
                                    }
                                }
                            }
                        });
                    }
                }
             }"
             x-init="init()"
        >
            <!-- Left panel: Line Chart -->
            <div class="lg:col-span-2 print:col-span-2 bg-gray-50/40 dark:bg-gray-800/20 border border-gray-150 dark:border-gray-800/80 rounded-2xl p-5 flex flex-col min-h-[360px] print:min-h-0 print:h-64 print:p-2 print:border-gray-300">
                <div class="text-sm font-bold text-gray-800 dark:text-gray-200 print:text-black mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500 print:bg-black"></span>
                    Grafik Tren Fluktuasi Bulanan (Januari - Desember)
                </div>
                <div class="relative flex-1 w-full min-h-[280px] print:h-52">
                    <canvas x-ref="lineCanvas"></canvas>
                </div>
            </div>

            <!-- Right panel: Doughnut Chart -->
            <div class="bg-gray-50/40 dark:bg-gray-800/20 border border-gray-150 dark:border-gray-800/80 rounded-2xl p-5 flex flex-col min-h-[360px] print:min-h-0 print:h-64 print:p-2 print:border-gray-300">
                <div class="text-sm font-bold text-gray-800 dark:text-gray-200 print:text-black mb-4 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-500 print:bg-black"></span>
                    @if($analysisType === 'tunggal')
                        Sebaran Rata-rata Bahan Pokok
                    @elseif($analysisType === 'komoditas')
                        Rata-rata Harga Komoditas Pembanding
                    @elseif($analysisType === 'wilayah')
                        Rata-rata Harga per Kelurahan/Desa
                    @elseif($analysisType === 'pasar')
                        Rata-rata Harga per Pasar Tradisional
                    @endif
                </div>
                <div class="relative flex-1 w-full flex items-center justify-center min-h-[280px] print:h-52">
                    <template x-if="!doughnutLabels || doughnutLabels.length === 0">
                        <div class="text-xs italic text-gray-400 dark:text-gray-500 text-center print:text-black">
                            Tidak ada data untuk diagram lingkaran
                        </div>
                    </template>
                    <canvas x-ref="doughnutCanvas" x-show="doughnutLabels && doughnutLabels.length > 0"></canvas>
                </div>
            </div>
        </div>

        <!-- ================= DETAILED DESCRIPTIVE ANALYSIS ================= -->
        <div class="print-hidden mb-8 p-6 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-1 h-5 bg-primary-500 rounded-full print:hidden"></div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white print:text-black uppercase tracking-wide">
                    Kesimpulan Analisis Data
                </h3>
            </div>
            <div class="text-sm leading-loose text-gray-600 dark:text-gray-300 font-medium print:text-black">
                {!! $analysisSummary !!}
            </div>
        </div>

        <!-- ================= STATISTICAL SUMMARY TABLE ================= -->
        <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden bg-white dark:bg-gray-900 print:border-black print:shadow-none">
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between print:bg-gray-100 print:border-black">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 rounded-lg print:hidden">
                        <x-filament::icon icon="heroicon-o-table-cells" class="w-5 h-5" />
                    </div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white print:text-black tracking-wide">
                        Tabel Ringkasan Statistik Bulanan
                    </h3>
                </div>
                <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 print:text-black uppercase tracking-wider bg-gray-50 dark:bg-gray-800 px-3 py-1 rounded-full">Mata Uang: Rupiah (IDR)</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="hpd-table w-full text-left text-sm border-collapse border border-gray-150 dark:border-gray-800 print:border-black">
                    @if($analysisType === 'tunggal')
                        <thead>
                            <tr class="bg-gray-100/55 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 font-semibold print:bg-gray-100 print:text-black">
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black">Bulan</th>
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">Harga Minimum</th>
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">Harga Rata-rata</th>
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">Harga Maksimum</th>
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-center">Fluktuasi Bulanan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach($analysisTableData as $row)
                                <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 print:text-black">
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black font-medium">{{ $row['bulan'] }}</td>
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">{{ $row['min'] ? 'Rp ' . number_format($row['min'], 0, ',', '.') : '-' }}</td>
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right font-semibold text-primary-600 dark:text-primary-400 print:text-black">{{ $row['avg'] ? 'Rp ' . number_format($row['avg'], 0, ',', '.') : '-' }}</td>
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">{{ $row['max'] ? 'Rp ' . number_format($row['max'], 0, ',', '.') : '-' }}</td>
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-center">
                                        @if(is_numeric($row['fluc']))
                                            @if($row['fluc'] > 0)
                                                <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 print:text-red-650 print:bg-transparent">
                                                    ▲ {{ $row['fluc'] }}%
                                                </span>
                                            @elseif($row['fluc'] < 0)
                                                <span class="inline-flex items-center text-xs font-semibold px-2 py-0.5 rounded-full bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400 print:text-green-650 print:bg-transparent">
                                                    ▼ {{ abs($row['fluc']) }}%
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-405 dark:text-gray-500">0%</span>
                                            @endif
                                        @else
                                            <span class="text-gray-450 dark:text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    @elseif($analysisType === 'komoditas')
                        @php
                            $coms = \App\Models\Komoditas::whereIn('id', $selectedKomoditasIds)->get();
                        @endphp
                        <thead>
                            <tr class="bg-gray-100/55 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 font-semibold print:bg-gray-100 print:text-black">
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black">Bulan</th>
                                @foreach($coms as $com)
                                    <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">{{ $com->nama_komoditas }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach($analysisTableData as $row)
                                <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 print:text-black">
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black font-medium">{{ $row['bulan'] }}</td>
                                    @foreach($coms as $com)
                                        <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">
                                            {{ is_numeric($row[$com->id]) ? 'Rp ' . number_format($row[$com->id], 0, ',', '.') : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    @elseif($analysisType === 'wilayah')
                        @php
                            $desList = \App\Models\desa::whereIn('id', $selectedDesaIds)->get();
                        @endphp
                        <thead>
                            <tr class="bg-gray-100/55 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 font-semibold print:bg-gray-100 print:text-black">
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black">Bulan</th>
                                @foreach($desList as $desa)
                                    <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">{{ $desa->nama_desa }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach($analysisTableData as $row)
                                <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 print:text-black">
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black font-medium">{{ $row['bulan'] }}</td>
                                    @foreach($desList as $desa)
                                        <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">
                                            {{ is_numeric($row[$desa->id]) ? 'Rp ' . number_format($row[$desa->id], 0, ',', '.') : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    @elseif($analysisType === 'pasar')
                        @php
                            $pasList = \App\Models\Pasar::whereIn('id', $selectedPasarIds)->get();
                        @endphp
                        <thead>
                            <tr class="bg-gray-100/55 dark:bg-gray-800/80 text-gray-700 dark:text-gray-300 font-semibold print:bg-gray-100 print:text-black">
                                <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black">Bulan</th>
                                @foreach($pasList as $pasar)
                                    <th class="py-3 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">{{ $pasar->nama_pasar }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/50">
                            @foreach($analysisTableData as $row)
                                <tr class="text-gray-600 dark:text-gray-300 hover:bg-gray-50/50 dark:hover:bg-gray-800/30 print:text-black">
                                    <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black font-medium">{{ $row['bulan'] }}</td>
                                    @foreach($pasList as $pasar)
                                        <td class="py-2.5 px-4 border border-gray-150 dark:border-gray-800 print:border-black text-right">
                                            {{ is_numeric($row[$pasar->id]) ? 'Rp ' . number_format($row[$pasar->id], 0, ',', '.') : '-' }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    @endif
                </table>
            </div>
        </div>

        <!-- ================= PRINT SIGNATURE BLOCK ================= -->
        <div class="print-sig-block print:flex print:justify-between print:mt-12 print:text-black" style="page-break-inside: avoid;">
            <div class="text-center w-56 text-black">
                <p class="text-xs">Mengetahui,</p>
                <p class="text-sm font-bold uppercase mt-1 mb-16 leading-tight">Kepala Dinas DKUMPP<br>Kabupaten Banjar</p>
                <div class="border-b border-black w-11/12 mx-auto"></div>
                <p class="text-xs font-semibold mt-1">NIP. 19750821 200003 1 002</p>
            </div>
            
            <div class="text-center w-56 text-black">
                <p class="text-xs">Martapura, {{ now()->translatedFormat('d F Y') }}</p>
                <p class="text-sm font-bold uppercase mt-1 mb-16 leading-tight">Petugas Pemantau Harga<br>DKUMPP Kab. Banjar</p>
                <div class="border-b border-black w-11/12 mx-auto"></div>
                <p class="text-xs font-semibold mt-1">NIP. 19891203 201504 2 001</p>
            </div>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
