<?php

use App\Http\Controllers\JastipController;
use App\Http\Controllers\PackagesController;
use App\Http\Controllers\PricingOptionController;
use App\Http\Controllers\ReportJastipController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
  return view('home.index');
});

Route::controller(PricingOptionController::class)->group(function () {
  Route::get('jenis-harga', 'index')->name('jenis-harga.index');
  Route::post('jenis-harga', 'store')->name('jenis-harga.store');
  Route::get('jenis-harga/get-data', 'getPricingOptions')->name('jenis-harga.getData');
  Route::post('jenis-harga/update/{pricing_option}', 'update')->name('jenis-harga.update');
  Route::delete('jenis-harga/destroy/{pricing_option}', 'destroy')->name('jenis-harga.destroy');
});

Route::controller(StatusController::class)->group(function () {
  Route::get('status', 'index')->name('status.index');
  Route::post('status', 'store')->name('status.store');
  Route::get('status/get-data', 'getStatuses')->name('status.getData');
  Route::post('status/update/{status}', 'update')->name('status.update');
  Route::delete('status/destroy/{status}', 'destroy')->name('status.destroy');
});

Route::get('jastip/get-data', [JastipController::class, 'getJastip'])->name('jastip.getData');
Route::get('jastip/get-data/{id}', [JastipController::class, 'getJastipById'])->name('jastip.getDataById');
Route::controller(PackagesController::class)->group(function () {
  Route::get('jastip/diterima', 'index')->name('jastip.received');
  Route::post('jastip/simpan-laporan', 'store')->name('jastip.saveJastipReport');
});

Route::resource('jastip', JastipController::class);

Route::prefix('laporan-jastip')->controller(ReportJastipController::class)->group(function () {
  Route::get('/', 'index')->name('laporan-jastip.index');
  Route::get('harian', 'daily')->name('laporan-jastip.daily');
  Route::get('get-daily-data', 'getDailyReportJastip')->name('laporan-jastip.getDailyData');
  Route::post('get-report-data', 'getReportJastip')->name('laporan-jastip.getReportData');
  Route::get('export-daily-pdf', 'exportDailyPdf')->name('laporan-jastip.exportDailyPdf');
  Route::post('export-report-pdf', 'exportReportPdf')->name('laporan-jastip.exportReportPdf');
});
