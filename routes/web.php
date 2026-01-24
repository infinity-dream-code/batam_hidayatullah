<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

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

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard Routes - langsung ke rekap pembayaran
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard.rekap-pembayaran');
    })->name('dashboard');
    
    Route::get('/rekap-pembayaran', [DashboardController::class, 'rekapPembayaran'])->name('dashboard.rekap-pembayaran');
    Route::post('/api/fetch-pembayaran', [DashboardController::class, 'fetchPembayaran'])->name('api.fetch-pembayaran');
    Route::get('/api/get-kelas', [DashboardController::class, 'getKelas'])->name('api.get-kelas');
    Route::get('/api/get-akun', [DashboardController::class, 'getAkun'])->name('api.get-akun');
    
    // Cetak Routes
    Route::get('/cetak/rekap/pdf', [DashboardController::class, 'cetakRekapPDF'])->name('cetak.rekap.pdf');
    Route::get('/cetak/rekap/excel', [DashboardController::class, 'cetakRekapExcel'])->name('cetak.rekap.excel');
    Route::get('/cetak/nis/pdf', [DashboardController::class, 'cetakNISPDF'])->name('cetak.nis.pdf');
    Route::get('/cetak/nis/excel', [DashboardController::class, 'cetakNISExcel'])->name('cetak.nis.excel');
});
