<?php

namespace App\Filament\Widgets;

use App\Models\desa;
use App\Models\Komoditas;
use App\Models\InputHarga;
use App\Models\RekapHarga;
use App\Models\Pasar;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class HargaPerDesaWidget extends Widget
{
    protected static ?int $sort = 3;
    protected int|string|array $columnSpan = '12';
    protected string $view = 'filament.widgets.harga-per-desa-widget';

    // Filters and Analysis configuration
    public string $analysisType = 'tunggal'; // 'tunggal', 'komoditas', 'wilayah', 'pasar'
    public string $locationType = 'desa'; // 'desa', 'pasar'
    
    public ?string $filterDesaId = null;
    public ?string $filterPasarId = null;
    public ?string $filterKomoditasId = null;
    public ?string $filterTahun = null;

    // Checkboxes / Multi-select values for comparison modes
    public array $selectedKomoditasIds = [];
    public array $selectedDesaIds = [];
    public array $selectedPasarIds = [];

    // Output variables for frontend Chart.js and rendering
    public array $lineLabels = [];
    public array $chartDatasets = [];
    
    public array $doughnutLabels = [];
    public array $doughnutValues = [];
    
    public array $analysisTableData = [];
    public string $analysisSummary = '';

    public function mount()
    {
        $this->filterDesaId = desa::orderBy('nama_desa')->first()?->id;
        $this->filterPasarId = Pasar::orderBy('nama_pasar')->first()?->id;
        $this->filterKomoditasId = Komoditas::orderBy('nama_komoditas')->first()?->id;
        $this->filterTahun = (string) date('Y');
        
        $this->analysisType = 'tunggal';
        $this->locationType = 'desa';

        // Select first 3 commodities for comparison
        $this->selectedKomoditasIds = Komoditas::orderBy('nama_komoditas')->limit(3)->pluck('id')->toArray();
        
        // Select first 3 desas for comparison
        $this->selectedDesaIds = desa::whereIn('id', [1, 6, 10])->pluck('id')->toArray();
        if (empty($this->selectedDesaIds)) {
            $this->selectedDesaIds = desa::orderBy('nama_desa')->limit(3)->pluck('id')->toArray();
        }
        
        // Select first 2 pasars for comparison
        $this->selectedPasarIds = Pasar::orderBy('nama_pasar')->limit(2)->pluck('id')->toArray();

        $this->updateChartData();
    }

    public function updated($propertyName)
    {
        // If we switch to 'wilayah' or 'pasar', ensure we have valid selections
        if ($propertyName === 'analysisType') {
            if ($this->analysisType === 'wilayah' && empty($this->selectedDesaIds)) {
                $this->selectedDesaIds = $this->getActiveDesaIdsLimit();
            }
            if ($this->analysisType === 'pasar' && empty($this->selectedPasarIds)) {
                $this->selectedPasarIds = $this->getActivePasarIdsLimit();
            }
        }
        $this->updateChartData();
    }

    protected function getActiveDesaIdsLimit(): array
    {
        $activeDesas = InputHarga::whereYear('tanggal_input', $this->filterTahun)
            ->distinct()
            ->limit(3)
            ->pluck('desa_id')
            ->toArray();
        if (empty($activeDesas)) {
            $activeDesas = desa::limit(3)->pluck('id')->toArray();
        }
        return $activeDesas;
    }

    protected function getActivePasarIdsLimit(): array
    {
        $activePasars = InputHarga::whereYear('tanggal_input', $this->filterTahun)
            ->distinct()
            ->limit(2)
            ->pluck('pasar_id')
            ->toArray();
        if (empty($activePasars)) {
            $activePasars = Pasar::limit(2)->pluck('id')->toArray();
        }
        return $activePasars;
    }

    public function updateChartData()
    {
        $monthsList = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];
        
        $this->lineLabels = array_values($monthsList);
        
        $colors = [
            '#3b82f6', // Blue
            '#10b981', // Green
            '#f59e0b', // Amber
            '#ef4444', // Red
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#ec4899', // Pink
            '#14b8a6', // Teal
            '#f43f5e', // Rose
            '#6366f1', // Indigo
        ];
        
        $this->chartDatasets = [];
        $this->doughnutLabels = [];
        $this->doughnutValues = [];
        $this->analysisTableData = [];
        $this->analysisSummary = '';

        if ($this->analysisType === 'tunggal') {
            // Mode 1: Single Commodity Trend (Min, Avg, Max)
            $query = InputHarga::query()
                ->whereYear('tanggal_input', $this->filterTahun)
                ->where('komoditas_id', $this->filterKomoditasId);
            
            if ($this->locationType === 'desa') {
                $query->where('desa_id', $this->filterDesaId);
                $locName = desa::find($this->filterDesaId)?->nama_desa ?? 'Desa';
            } else {
                $query->where('pasar_id', $this->filterPasarId);
                $locName = Pasar::find($this->filterPasarId)?->nama_pasar ?? 'Pasar';
            }
            
            $raw = $query->selectRaw("
                MONTH(tanggal_input) as bulan,
                AVG(harga) as avg_harga,
                MAX(harga) as max_harga,
                MIN(harga) as min_harga
            ")
            ->groupBy('bulan')
            ->get()
            ->keyBy('bulan');
            
            $avgData = [];
            $maxData = [];
            $minData = [];
            
            for ($m = 1; $m <= 12; $m++) {
                if (isset($raw[$m])) {
                    $avg = round($raw[$m]->avg_harga, 0);
                    $max = round($raw[$m]->max_harga, 0);
                    $min = round($raw[$m]->min_harga, 0);
                    
                    $avgData[] = (float) $avg;
                    $maxData[] = (float) $max;
                    $minData[] = (float) $min;
                    
                    // Calc fluctuation %
                    $prevMonthAvg = isset($raw[$m-1]) ? $raw[$m-1]->avg_harga : null;
                    $fluc = 0;
                    if ($prevMonthAvg && $prevMonthAvg > 0) {
                        $fluc = (($raw[$m]->avg_harga - $prevMonthAvg) / $prevMonthAvg) * 100;
                    }
                    
                    $this->analysisTableData[] = [
                        'bulan' => $monthsList[$m],
                        'min' => $min,
                        'avg' => $avg,
                        'max' => $max,
                        'fluc' => round($fluc, 1),
                    ];
                } else {
                    $avgData[] = null;
                    $maxData[] = null;
                    $minData[] = null;
                    
                    $this->analysisTableData[] = [
                        'bulan' => $monthsList[$m],
                        'min' => null,
                        'avg' => null,
                        'max' => null,
                        'fluc' => null,
                    ];
                }
            }
            
            $this->chartDatasets = [
                [
                    'label' => 'Harga Rata-rata',
                    'data' => $avgData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.05)',
                    'fill' => true,
                    'tension' => 0.3,
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                ],
                [
                    'label' => 'Harga Maksimum',
                    'data' => $maxData,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                    'borderWidth' => 1.5,
                    'borderDash' => [5, 5],
                    'pointRadius' => 3,
                ],
                [
                    'label' => 'Harga Minimum',
                    'data' => $minData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                    'borderWidth' => 1.5,
                    'borderDash' => [5, 5],
                    'pointRadius' => 3,
                ]
            ];
            
            // Doughnut Data: Average of other commodities in this location
            $doughnutRaw = InputHarga::query()
                ->whereYear('tanggal_input', $this->filterTahun);
            if ($this->locationType === 'desa') {
                $doughnutRaw->where('desa_id', $this->filterDesaId);
            } else {
                $doughnutRaw->where('pasar_id', $this->filterPasarId);
            }
            $doughnutRaw = $doughnutRaw->selectRaw("komoditas_id, AVG(harga) as avg_harga")
                ->groupBy('komoditas_id')
                ->with('komoditas')
                ->get();
            
            $this->doughnutLabels = $doughnutRaw->map(fn($item) => $item->komoditas?->nama_komoditas ?? 'Komoditas')->toArray();
            $this->doughnutValues = $doughnutRaw->map(fn($item) => (float) round($item->avg_harga, 0))->toArray();
            
            // Summary Text
            $comName = Komoditas::find($this->filterKomoditasId)?->nama_komoditas ?? 'Bahan Pokok';
            $validAvgs = array_filter($avgData, fn($v) => !is_null($v));
            
            if (count($validAvgs) > 0) {
                $overallAvg = array_sum($validAvgs) / count($validAvgs);
                $maxVal = max($validAvgs);
                $minVal = min($validAvgs);
                
                $maxMonthIdx = array_search($maxVal, $avgData) + 1;
                $minMonthIdx = array_search($minVal, $avgData) + 1;
                
                $maxMonth = $monthsList[$maxMonthIdx];
                $minMonth = $monthsList[$minMonthIdx];
                
                $firstMonthIdx = null;
                $lastMonthIdx = null;
                foreach ($avgData as $idx => $val) {
                    if (!is_null($val)) {
                        if (is_null($firstMonthIdx)) $firstMonthIdx = $idx;
                        $lastMonthIdx = $idx;
                    }
                }
                
                $firstVal = $avgData[$firstMonthIdx];
                $lastVal = $avgData[$lastMonthIdx];
                $diff = $lastVal - $firstVal;
                $pct = $firstVal > 0 ? ($diff / $firstVal) * 100 : 0;
                $arah = $diff >= 0 ? 'kenaikan' : 'penurunan';
                
                $this->analysisSummary = "Berdasarkan pemantauan tahun <strong>{$this->filterTahun}</strong>, komoditas <strong>{$comName}</strong> di <strong>{$locName}</strong> memiliki rata-rata harga tahunan sebesar <strong>Rp " . number_format($overallAvg, 0, ',', '.') . "</strong>. "
                    . "Harga rata-rata tertinggi tercatat pada bulan <strong>{$maxMonth}</strong> mencapai <strong>Rp " . number_format($maxVal, 0, ',', '.') . "</strong>, sedangkan harga rata-rata terendah tercatat pada bulan <strong>{$minMonth}</strong> sebesar <strong>Rp " . number_format($minVal, 0, ',', '.') . "</strong>. "
                    . "Secara umum, harga komoditas ini mengalami <strong>{$arah}</strong> sebesar <strong>" . number_format(abs($pct), 1) . "%</strong> dari bulan {$monthsList[$firstMonthIdx + 1]} ke bulan {$monthsList[$lastMonthIdx + 1]}. Fluktuasi ini dipengaruhi oleh dinamika pasokan, biaya logistik, dan faktor musiman.";
            } else {
                $this->analysisSummary = "Belum ada data pemantauan harga komoditas <strong>{$comName}</strong> di <strong>{$locName}</strong> pada tahun <strong>{$this->filterTahun}</strong>.";
            }
            
        } elseif ($this->analysisType === 'komoditas') {
            // Mode 2: Compare Commodities
            if (empty($this->selectedKomoditasIds)) {
                $this->selectedKomoditasIds = Komoditas::limit(3)->pluck('id')->toArray();
            }
            
            if ($this->locationType === 'desa') {
                $locName = desa::find($this->filterDesaId)?->nama_desa ?? 'Desa';
            } else {
                $locName = Pasar::find($this->filterPasarId)?->nama_pasar ?? 'Pasar';
            }
            
            $comoditiesList = Komoditas::whereIn('id', $this->selectedKomoditasIds)->get();
            $comparisonData = [];
            
            foreach ($comoditiesList as $idx => $komoditas) {
                $query = InputHarga::query()
                    ->whereYear('tanggal_input', $this->filterTahun)
                    ->where('komoditas_id', $komoditas->id);
                
                if ($this->locationType === 'desa') {
                    $query->where('desa_id', $this->filterDesaId);
                } else {
                    $query->where('pasar_id', $this->filterPasarId);
                }
                
                $raw = $query->selectRaw("MONTH(tanggal_input) as bulan, AVG(harga) as avg_harga")
                    ->groupBy('bulan')
                    ->get()
                    ->keyBy('bulan');
                
                $dataVal = [];
                for ($m = 1; $m <= 12; $m++) {
                    $dataVal[] = isset($raw[$m]) ? (float) round($raw[$m]->avg_harga, 0) : null;
                }
                
                $color = $colors[$idx % count($colors)];
                $this->chartDatasets[] = [
                    'label' => $komoditas->nama_komoditas,
                    'data' => $dataVal,
                    'borderColor' => $color,
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                    'borderWidth' => 2.5,
                    'pointRadius' => 4,
                ];
                
                $validVals = array_filter($dataVal, fn($v) => !is_null($v));
                $avgYear = count($validVals) > 0 ? array_sum($validVals) / count($validVals) : 0;
                
                if ($avgYear > 0) {
                    $this->doughnutLabels[] = $komoditas->nama_komoditas;
                    $this->doughnutValues[] = (float) round($avgYear, 0);
                }
                
                $comparisonData[$komoditas->nama_komoditas] = $dataVal;
            }
            
            // Build Table Data
            for ($m = 1; $m <= 12; $m++) {
                $row = ['bulan' => $monthsList[$m]];
                foreach ($comoditiesList as $komoditas) {
                    $val = $comparisonData[$komoditas->nama_komoditas][$m - 1] ?? null;
                    $row[$komoditas->id] = $val ? $val : null;
                }
                $this->analysisTableData[] = $row;
            }
            
            if (count($this->doughnutValues) > 0) {
                $maxValIdx = array_search(max($this->doughnutValues), $this->doughnutValues);
                $minValIdx = array_search(min($this->doughnutValues), $this->doughnutValues);
                
                $maxCom = $this->doughnutLabels[$maxValIdx];
                $minCom = $this->doughnutLabels[$minValIdx];
                
                $this->analysisSummary = "Perbandingan harga komoditas di <strong>{$locName}</strong> tahun <strong>{$this->filterTahun}</strong> menunjukkan perbedaan nilai rata-rata tahunan. "
                    . "Komoditas dengan tingkat harga tertinggi rata-rata adalah <strong>{$maxCom}</strong> sebesar <strong>Rp " . number_format($this->doughnutValues[$maxValIdx], 0, ',', '.') . "</strong>. "
                    . "Sedangkan komoditas dengan tingkat harga terendah rata-rata adalah <strong>{$minCom}</strong> sebesar <strong>Rp " . number_format($this->doughnutValues[$minValIdx], 0, ',', '.') . "</strong>. "
                    . "Grafik di atas memvisualisasikan pergerakan harga komoditas ini saling berkaitan sepanjang tahun.";
            } else {
                $this->analysisSummary = "Belum ada data pemantauan untuk komoditas terpilih di <strong>{$locName}</strong> pada tahun <strong>{$this->filterTahun}</strong>.";
            }
            
        } elseif ($this->analysisType === 'wilayah') {
            // Mode 3: Compare Kelurahan/Desa
            if (empty($this->selectedDesaIds)) {
                $this->selectedDesaIds = desa::limit(3)->pluck('id')->toArray();
            }
            
            $comName = Komoditas::find($this->filterKomoditasId)?->nama_komoditas ?? 'Bahan Pokok';
            $desaList = desa::whereIn('id', $this->selectedDesaIds)->get();
            $comparisonData = [];
            
            foreach ($desaList as $idx => $desa) {
                $raw = InputHarga::query()
                    ->whereYear('tanggal_input', $this->filterTahun)
                    ->where('komoditas_id', $this->filterKomoditasId)
                    ->where('desa_id', $desa->id)
                    ->selectRaw("MONTH(tanggal_input) as bulan, AVG(harga) as avg_harga")
                    ->groupBy('bulan')
                    ->get()
                    ->keyBy('bulan');
                
                $dataVal = [];
                for ($m = 1; $m <= 12; $m++) {
                    $dataVal[] = isset($raw[$m]) ? (float) round($raw[$m]->avg_harga, 0) : null;
                }
                
                $color = $colors[$idx % count($colors)];
                $this->chartDatasets[] = [
                    'label' => $desa->nama_desa,
                    'data' => $dataVal,
                    'borderColor' => $color,
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                    'borderWidth' => 2.5,
                    'pointRadius' => 4,
                ];
                
                $validVals = array_filter($dataVal, fn($v) => !is_null($v));
                $avgYear = count($validVals) > 0 ? array_sum($validVals) / count($validVals) : 0;
                
                if ($avgYear > 0) {
                    $this->doughnutLabels[] = $desa->nama_desa;
                    $this->doughnutValues[] = (float) round($avgYear, 0);
                }
                
                $comparisonData[$desa->nama_desa] = $dataVal;
            }
            
            // Build Table Data
            for ($m = 1; $m <= 12; $m++) {
                $row = ['bulan' => $monthsList[$m]];
                foreach ($desaList as $desa) {
                    $val = $comparisonData[$desa->nama_desa][$m - 1] ?? null;
                    $row[$desa->id] = $val ? $val : null;
                }
                $this->analysisTableData[] = $row;
            }
            
            if (count($this->doughnutValues) > 0) {
                $maxValIdx = array_search(max($this->doughnutValues), $this->doughnutValues);
                $minValIdx = array_search(min($this->doughnutValues), $this->doughnutValues);
                
                $maxDesa = $this->doughnutLabels[$maxValIdx];
                $minDesa = $this->doughnutLabels[$minValIdx];
                
                $this->analysisSummary = "Perbandingan harga komoditas <strong>{$comName}</strong> antar kelurahan/desa pada tahun <strong>{$this->filterTahun}</strong> menunjukkan disparitas harga wilayah. "
                    . "Wilayah dengan tingkat harga rata-rata tertinggi adalah <strong>{$maxDesa}</strong> dengan nilai <strong>Rp " . number_format($this->doughnutValues[$maxValIdx], 0, ',', '.') . "</strong>. "
                    . "Sedangkan wilayah dengan tingkat harga rata-rata terendah adalah <strong>{$minDesa}</strong> dengan nilai <strong>Rp " . number_format($this->doughnutValues[$minValIdx], 0, ',', '.') . "</strong>. "
                    . "Disparitas harga ini mencerminkan perbedaan aksesibilitas, jarak dari pusat distribusi, dan pasokan lokal.";
            } else {
                $this->analysisSummary = "Belum ada data pemantauan harga komoditas <strong>{$comName}</strong> di desa-desa terpilih pada tahun <strong>{$this->filterTahun}</strong>.";
            }
            
        } elseif ($this->analysisType === 'pasar') {
            // Mode 4: Compare Pasars
            if (empty($this->selectedPasarIds)) {
                $this->selectedPasarIds = Pasar::limit(2)->pluck('id')->toArray();
            }
            
            $comName = Komoditas::find($this->filterKomoditasId)?->nama_komoditas ?? 'Bahan Pokok';
            $pasarList = Pasar::whereIn('id', $this->selectedPasarIds)->get();
            $comparisonData = [];
            
            foreach ($pasarList as $idx => $pasar) {
                $raw = InputHarga::query()
                    ->whereYear('tanggal_input', $this->filterTahun)
                    ->where('komoditas_id', $this->filterKomoditasId)
                    ->where('pasar_id', $pasar->id)
                    ->selectRaw("MONTH(tanggal_input) as bulan, AVG(harga) as avg_harga")
                    ->groupBy('bulan')
                    ->get()
                    ->keyBy('bulan');
                
                $dataVal = [];
                for ($m = 1; $m <= 12; $m++) {
                    $dataVal[] = isset($raw[$m]) ? (float) round($raw[$m]->avg_harga, 0) : null;
                }
                
                $color = $colors[$idx % count($colors)];
                $this->chartDatasets[] = [
                    'label' => $pasar->nama_pasar,
                    'data' => $dataVal,
                    'borderColor' => $color,
                    'backgroundColor' => 'transparent',
                    'tension' => 0.3,
                    'borderWidth' => 2.5,
                    'pointRadius' => 4,
                ];
                
                $validVals = array_filter($dataVal, fn($v) => !is_null($v));
                $avgYear = count($validVals) > 0 ? array_sum($validVals) / count($validVals) : 0;
                
                if ($avgYear > 0) {
                    $this->doughnutLabels[] = $pasar->nama_pasar;
                    $this->doughnutValues[] = (float) round($avgYear, 0);
                }
                
                $comparisonData[$pasar->nama_pasar] = $dataVal;
            }
            
            // Build Table Data
            for ($m = 1; $m <= 12; $m++) {
                $row = ['bulan' => $monthsList[$m]];
                foreach ($pasarList as $pasar) {
                    $val = $comparisonData[$pasar->nama_pasar][$m - 1] ?? null;
                    $row[$pasar->id] = $val ? $val : null;
                }
                $this->analysisTableData[] = $row;
            }
            
            if (count($this->doughnutValues) > 0) {
                $maxValIdx = array_search(max($this->doughnutValues), $this->doughnutValues);
                $minValIdx = array_search(min($this->doughnutValues), $this->doughnutValues);
                
                $maxPasar = $this->doughnutLabels[$maxValIdx];
                $minPasar = $this->doughnutLabels[$minValIdx];
                
                $this->analysisSummary = "Perbandingan harga komoditas <strong>{$comName}</strong> antar pasar pada tahun <strong>{$this->filterTahun}</strong> menunjukkan disparitas harga tingkat pedagang pasar. "
                    . "Pasar dengan tingkat harga rata-rata tertinggi adalah <strong>{$maxPasar}</strong> dengan nilai <strong>Rp " . number_format($this->doughnutValues[$maxValIdx], 0, ',', '.') . "</strong>. "
                    . "Sedangkan pasar dengan tingkat harga rata-rata terendah adalah <strong>{$minPasar}</strong> dengan nilai <strong>Rp " . number_format($this->doughnutValues[$minValIdx], 0, ',', '.') . "</strong>. "
                    . "Perbedaan harga ini biasanya mencerminkan perbedaan biaya retribusi, volume transaksi, dan pasokan dari distributor utama.";
            } else {
                $this->analysisSummary = "Belum ada data pemantauan harga komoditas <strong>{$comName}</strong> di pasar-pasar terpilih pada tahun <strong>{$this->filterTahun}</strong>.";
            }
        }
    }

    public function getDesaOptions(): array
    {
        return desa::orderBy('nama_desa')->pluck('nama_desa', 'id')->toArray();
    }

    public function getActiveDesaOptions(): array
    {
        $activeDesas = InputHarga::whereYear('tanggal_input', $this->filterTahun)
            ->distinct()
            ->pluck('desa_id')
            ->toArray();
            
        $options = desa::whereIn('id', $activeDesas)->orderBy('nama_desa')->pluck('nama_desa', 'id')->toArray();
        
        if (empty($options)) {
            return desa::orderBy('nama_desa')->limit(12)->pluck('nama_desa', 'id')->toArray();
        }
        
        return $options;
    }

    public function getPasarOptions(): array
    {
        return Pasar::orderBy('nama_pasar')->pluck('nama_pasar', 'id')->toArray();
    }

    public function getActivePasarOptions(): array
    {
        $activePasars = InputHarga::whereYear('tanggal_input', $this->filterTahun)
            ->distinct()
            ->pluck('pasar_id')
            ->toArray();
            
        $options = Pasar::whereIn('id', $activePasars)->orderBy('nama_pasar')->pluck('nama_pasar', 'id')->toArray();
        
        if (empty($options)) {
            return Pasar::orderBy('nama_pasar')->limit(12)->pluck('nama_pasar', 'id')->toArray();
        }
        
        return $options;
    }

    public function getKomoditasOptions(): array
    {
        return Komoditas::orderBy('nama_komoditas')->pluck('nama_komoditas', 'id')->toArray();
    }

    public function getTahunOptions(): array
    {
        $years = range(date('Y') - 3, date('Y'));
        return array_combine($years, $years);
    }
}
