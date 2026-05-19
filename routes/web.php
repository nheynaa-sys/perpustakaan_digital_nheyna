<?php

use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

// ============================================================
// ROOT
// ============================================================
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// ============================================================
// AUTH LOGIN ANGGOTA (NIS)
// ============================================================
Route::middleware('guest')->group(function () {
    Route::get('/login-anggota', [AuthenticatedSessionController::class, 'createAnggota'])
        ->name('login.anggota');
    Route::post('/login-anggota', [AuthenticatedSessionController::class, 'storeAnggota'])
        ->name('login.anggota.submit');
});

// ============================================================
// DASHBOARD REDIRECT
// ============================================================
Route::get('/dashboard', function () {
    return auth()->user()->role === 'admin'
        ? redirect()->route('admin.dashboard')
        : redirect()->route('user.katalog');
})->middleware('auth')->name('dashboard');

// ============================================================
// ROUTE SEMUA USER LOGIN (admin & anggota)
// ============================================================
Route::middleware(['auth'])->group(function () {

    // Profil
    Route::get('/profile',          [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit',     [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',        [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile',       [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Buku — index (semua user)
    Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

    // Buku — CRUD admin (spesifik dulu, SEBELUM route {buku})
    Route::middleware('role:admin')->group(function () {
        Route::get('/buku/create',          [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku',                [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit',     [BukuController::class, 'edit'])->name('buku.edit');
        Route::patch('/buku/{buku}',        [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}',       [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    // Buku — show (semua user, HARUS PALING BAWAH)
    Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
});

// ============================================================
// ROUTE ADMIN
// ============================================================
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');

    // Anggota — CRUD penuh
    Route::resource('anggota', AnggotaController::class)
        ->parameters(['anggota' => 'anggota']);

    // Kategori — tanpa show
    Route::resource('kategori', KategoriController::class)->except(['show']);

    // Peminjaman — route spesifik SEBELUM route {peminjaman}
    Route::get('/peminjaman/pending',   [PeminjamanController::class, 'pending'])->name('peminjaman.pending');
    Route::get('/peminjaman/create',    [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::get('/peminjaman',           [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::post('/peminjaman',          [PeminjamanController::class, 'store'])->name('peminjaman.store');

    // Peminjaman — route dengan {peminjaman}
    Route::get('/peminjaman/{peminjaman}/struk',           [PeminjamanController::class, 'struk'])->name('peminjaman.struk');
    Route::patch('/peminjaman/{peminjaman}/approve',       [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::patch('/peminjaman/{peminjaman}/reject',        [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::patch('/peminjaman/{peminjaman}/approve-struk', [PeminjamanController::class, 'approveStruk'])->name('peminjaman.approveStruk');
    Route::post('/peminjaman/{id}/kembalikan',             [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::delete('/peminjaman/{peminjaman}',              [PeminjamanController::class, 'destroy'])->name('peminjaman.destroy');

    // Peminjaman — show PALING BAWAH
    Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
});

// ============================================================
// ROUTE ANGGOTA
// ============================================================
Route::middleware(['auth', 'role:anggota'])->group(function () {

    // Update data anggota
    Route::patch('/profile/anggota', [ProfileController::class, 'updateAnggota'])
        ->name('profile.anggota.update');

    // Katalog & peminjaman mandiri
    Route::get('/katalog',         [PeminjamanController::class, 'katalog'])->name('user.katalog');
    Route::post('/katalog/ajukan', [PeminjamanController::class, 'ajukan'])->name('user.ajukan');
    Route::get('/riwayat-saya',    [PeminjamanController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/riwayat-saya/{peminjaman}/struk', [PeminjamanController::class, 'struk'])->name('user.struk');
});

// ============================================================
// AUTH (BREEZE)
// ============================================================
require __DIR__ . '/auth.php';