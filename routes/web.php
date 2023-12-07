<?php

use App\Models\Category;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

use App\Http\Controllers\PiutangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KasController;
use App\Http\Controllers\CashFlowController;
use App\Http\Controllers\JurnalAkunController;
use App\Http\Controllers\RkatController;

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

Route::get('password', [ChangePasswordController::class, 'edit'])->name('password.edit')->middleware('auth');
Route::patch('password', [ChangePasswordController::class, 'update'])->name('password.edit')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Menu
Route::get('/piutang', [PiutangController::class, 'index'])->middleware('auth')->name('piutang');
Route::get('/riwayatPiutang', [PiutangController::class, 'riwayatPiutang'])->middleware('auth')->name('riwayatPiutang');
Route::get('/printPiutang', [PiutangController::class, 'printPiutang'])->middleware('auth')->name('printPiutang');
Route::post('/storePiutang', [PiutangController::class, 'storePiutang'])->middleware('auth')->name('insert.piutang');
Route::get('/realisasiPiutang/{id}', [PiutangController::class, 'realisasiPiutang'])->middleware('auth')->name('realisasi.piutang');
Route::post('/updaterealisasiPiutang/{id}', [PiutangController::class, 'realisasi'])->middleware('auth')->name('update.realisasi');


// Menu Cash Flow
Route::get('/cashflow', [CashFlowController::class, 'index'])->middleware('auth')->name('cashflow');
Route::post('/cashflow', [CashFlowController::class, 'store'])->middleware('auth')->name('insert.cashflow');
Route::get('/lapcashflow', [CashFlowController::class, 'laporan'])->middleware('auth')->name('lapcashflow'); // Laporan Cash Flow
Route::get('/printcashflow/{start_date}/{end_date}', [CashFlowController::class, 'printCashFlow'])->middleware('auth')->name('printcashflow'); // Print Laporan Cash Flow
Route::get('/exportcashflow/{start_date}/{end_date}', [CashFlowController::class, 'exportCashFlow'])->middleware('auth')->name('exportcashflow'); // Export Excel Cash Flow

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
