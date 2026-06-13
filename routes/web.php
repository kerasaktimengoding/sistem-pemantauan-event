<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WilayahPDFController;
use App\Http\Controllers\KecamatanPDFController;
use App\Http\Controllers\DesaPDFController;
use App\Http\Controllers\JabatanPDFController;
use App\Http\Controllers\PegawaiPDFController;
use App\Http\Controllers\TempatPDFController;
use App\Http\Controllers\KomoditasPDFController;
use App\Http\Controllers\PasarPDFController;
use App\Http\Controllers\PedagangPDFController;
use App\Http\Controllers\JadwalPDFController;
use App\Http\Controllers\InputPDFController;
use App\Http\Controllers\EventPDFController;
use App\Http\Controllers\PesertaPDFController;
use App\Http\Controllers\KehadiranPDFController;
use App\Http\Controllers\HasilPDFController;
use App\Http\Controllers\DetailPDFController;
use App\Http\Controllers\RekapHargaPDFController;
use App\Http\Controllers\TrenHargaPDFController;
use App\Http\Controllers\PerbandinganWilayahPDFController;



Route::get('/', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('/login', function () {
    return redirect()->route('filament.admin.auth.login');
});

Route::get('download1', [KecamatanPDFController::class, 'downloadKecamatanPDF'])->name('download1.tes1');
Route::get('download2', [DesaPDFController::class, 'downdloadDesaPDF'])->name('download2.tes2');
Route::get('download3', [WilayahPDFController::class, 'downloadWilayahPDF'])->name('download3.tes3');
Route::get('download4', [JabatanPDFController::class, 'downloadJabatanPDF'])->name('download4.tes4');
Route::get('download5', [PegawaiPDFController::class, 'downloadPegawaiPDF'])->name('download5.tes5');
Route::get('download6', [TempatPDFController::class, 'downloadTempatPDF'])->name('download6.tes6');
Route::get('download7', [KomoditasPDFController::class, 'downloadKomoditasPDF'])->name('download7.tes7');
Route::get('download8', [PasarPDFController::class, 'downloadPasarPDF'])->name('download8.tes8');
Route::get('download9', [PedagangPDFController::class, 'downloadPedagangPDF'])->name('download9.tes9');
Route::get('download10', [JadwalPDFController::class, 'downloadJadwalPDF'])->name('download10.tes10');
Route::get('download11', [InputPDFController::class, 'downloadInputPDF'])->name('download11.tes11');
Route::get('download12', [EventPDFController::class, 'downloadEventPDF'])->name('download12.tes12');
Route::get('download13', [PesertaPDFController::class, 'downloadPesertaPDF'])->name('download13.tes13');
Route::get('download14', [KehadiranPDFController::class, 'downloadKehadiranPDF'])->name('download14.tes14');
Route::get('download15', [HasilPDFController::class, 'downloadHasilPDF'])->name('download15.tes15');
Route::get('download16', [DetailPDFController::class, 'downloadDetailPDF'])->name('download16.tes16');
Route::get('download17', [RekapHargaPDFController::class, 'downloadRekapHargaPDF'])->name('download17.tes17');
Route::get('download18', [TrenHargaPDFController::class, 'downloadRekapHargaPDF'])->name('download18.tes18');
Route::get('download19', [PerbandinganWilayahPDFController::class, 'downloadPerbandinganWilayahPDF'])->name('download19.tes19');