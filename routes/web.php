<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

use App\Http\Controllers\PiutangController;
use App\Http\Controllers\UangFisikController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\NeracaController;
use App\Http\Controllers\PecahanUangController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\JurnalAkunController;
use App\Http\Controllers\RkatController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\AktivitasController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login', [LoginController::class, 'index'])->middleware('guest')->name('login');
Route::get('/', [LoginController::class, 'index'])->middleware('guest')->name('login');

Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit')->middleware('auth');
// Route::patch('password', [ChangePasswordController::class, 'update'])->name('password.edit')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Menu Piutang
Route::get('/piutang', [PiutangController::class, 'index'])->middleware('auth')->name('piutang');
Route::get('/riwayatPiutang', [PiutangController::class, 'riwayatPiutang'])->middleware('auth')->name('riwayatPiutang');
Route::get('/printPiutang', [PiutangController::class, 'printPiutang'])->middleware('auth')->name('printPiutang');
Route::post('/storePiutang', [PiutangController::class, 'storePiutang'])->middleware('auth')->name('insert.piutang');
Route::get('/realisasiPiutang/{id}', [PiutangController::class, 'realisasiPiutang'])->middleware('auth')->name('realisasi.piutang');
Route::post('/updaterealisasiPiutang/{id}', [PiutangController::class, 'realisasi'])->middleware('auth')->name('update.realisasi');

// Menu Uang Fisik
Route::get('/uangFisik', [UangFisikController::class, 'index'])->middleware('auth')->name('uangFisik');
Route::post('/storeUangFisik', [UangFisikController::class, 'store'])->middleware('auth')->name('insert.uangFisik');
Route::delete('/deleteUangFisik/{id}', [UangFisikController::class, 'destroy'])->middleware('auth')->name('destroy.uangFisik');

// menu compare
Route::get('/compare', [CompareController::class, 'index'])->middleware('auth')->name('compare');
Route::get('/printCompare', [CompareController::class, 'printCompare'])->middleware('auth')->name('printCompare');

// menu neraca
Route::get('/neraca', [NeracaController::class, 'index'])->middleware('auth')->name('neraca');
Route::get('/printNeraca', [NeracaController::class, 'printNeraca'])->middleware('auth')->name('printNeraca');


// Menu Cash Flow
Route::get('/cashflow', [CashFlowController::class, 'index'])->middleware('auth')->name('cashflow');
Route::post('/cashflow', [CashFlowController::class, 'store'])->middleware('auth')->name('insert.cashflow');
Route::get('/lapcashflow', [CashFlowController::class, 'laporan'])->middleware('auth')->name('lapcashflow'); // Laporan Cash Flow
Route::get('/printcashflow/{start_date}/{end_date}', [CashFlowController::class, 'printCashFlow'])->middleware('auth')->name('printcashflow'); // Print Laporan Cash Flow
Route::get('/exportcashflow/{start_date}/{end_date}', [CashFlowController::class, 'exportCashFlow'])->middleware('auth')->name('exportcashflow'); // Export Excel Cash Flow

// Menu Jurnal
Route::get('/jurnal', [JurnalController::class, 'index'])->middleware('auth')->name('jurnal');
Route::get('/inputJurnal', [JurnalController::class, 'input'])->middleware('auth')->name('input.jurnal');
Route::post('/jurnalStore', [JurnalController::class, 'storeJurnal'])->middleware('auth')->name('store.jurnal');
Route::get('/import-jurnal', [JurnalController::class, 'showImportForm'])->name('import.jurnal.view');
Route::post('/import-jurnal', [JurnalController::class, 'importExcel'])->name('import.jurnal');
Route::get('download-example-excel-jurnal', [JurnalController::class, 'downloadExampleExcel'])->name('download.example.excel.jurnal');
Route::get('/laporanBukuBesar', [JurnalController::class, 'laporanBukuBesar'])->middleware('auth')->name('laporanBukuBesar');
Route::get('/printjurnal/{selectedYear}/{selectedMonth}', [JurnalController::class, 'printJurnal'])->middleware('auth')->name('printjurnal');
Route::get('/printbukubesar/{selectedYear}/{selectedMonth}/{selectedJurnalAccount}', [JurnalController::class, 'printBukuBesar'])->middleware('auth')->name('printbukubesar');

// Menu Aktivitas
Route::get('/aktivitas', [AktivitasController::class, 'index'])->middleware('auth')->name('aktivitas');
Route::get('/printAktivitas/{selectedYear}/{selectedMonth}', [AktivitasController::class, 'printAktivitas'])->middleware('auth')->name('printAktivitas');

// Master User
Route::get('/user', [UserController::class, 'index'])->middleware('auth')->name('user');
Route::post('/user', [UserController::class, 'store'])->middleware('auth')->name('insert.user');
Route::get('/editUser/{id}', [UserController::class, 'edit'])->middleware('auth')->name('edit.user');
Route::post('/updateUser/{id}', [UserController::class, 'update'])->middleware('auth')->name('update.user');
Route::delete('/deleteUser/{id}', [UserController::class, 'destroy'])->middleware('auth')->name('destroy.user');
Route::get('/resetUser/{id}', [UserController::class, 'reset'])->middleware('auth')->name('reset.user');
Route::post('/resetupdateUser/{id}', [UserController::class, 'resetupdate'])->middleware('auth')->name('resetupdate.user');

// Master Kas
Route::get('/kas', [KasController::class, 'index'])->middleware('auth')->name('kas');

// Master Jurnal Akun
Route::get('/jurnalakun', [JurnalAkunController::class, 'index'])->middleware('auth')->name('jurnalakun');
Route::post('/jurnalakun', [JurnalAkunController::class, 'store'])->middleware('auth')->name('insert.jurnalakun');
Route::get('/editJurnalakun/{id}', [JurnalAkunController::class, 'edit'])->middleware('auth')->name('edit.jurnalakun');
Route::post('/updateJurnalakun/{id}', [JurnalAkunController::class, 'update'])->middleware('auth')->name('update.jurnalakun');
Route::delete('/deleteJurnalakun/{id}', [JurnalAkunController::class, 'destroy'])->middleware('auth')->name('destroy.jurnalakun');

// Master RKAT
Route::get('/rkat', [RkatController::class, 'index'])->middleware('auth')->name('rkat');
Route::post('/rkat', [RkatController::class, 'store'])->middleware('auth')->name('insert.rkat');
Route::get('/editRkat/{id}', [RkatController::class, 'edit'])->middleware('auth')->name('edit.rkat');
Route::post('/updateRkat/{id}', [RkatController::class, 'update'])->middleware('auth')->name('update.rkat');
Route::delete('/deleteRkat/{id}', [RkatController::class, 'destroy'])->middleware('auth')->name('destroy.rkat');
Route::get('/import-rkat', [RkatController::class, 'showImportForm'])->name('import.rkat.view');
Route::post('/import-rkat', [RkatController::class, 'importExcel'])->name('import.rkat');
Route::get('download-example-excel', [RkatController::class, 'downloadExampleExcel'])->name('download.example.excel');
Route::get('/exportrkat/{periode}', [RkatController::class, 'exportRkat'])->middleware('auth')->name('exportrkat');

// Master Pecahan Uang
Route::get('/pecahan', [PecahanUangController::class, 'index'])->middleware('auth')->name('pecahan');
Route::post('/pecahan', [PecahanUangController::class, 'store'])->middleware('auth')->name('insert.pecahan');
Route::get('/editPecahan/{id}', [PecahanUangController::class, 'edit'])->middleware('auth')->name('edit.pecahan');
Route::post('/updatePecahan/{id}', [PecahanUangController::class, 'update'])->middleware('auth')->name('update.pecahan');
Route::delete('/deletePecahan/{id}', [PecahanUangController::class, 'destroy'])->middleware('auth')->name('destroy.pecahan');


