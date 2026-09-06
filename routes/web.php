<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KalenderController;
use App\Http\Controllers\Admin\MahasiswaController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\GuestController;
use Illuminate\Support\Facades\Route;

// ================== HALAMAN PUBLIK / GUEST (tanpa login, dicek maintenance mode) ==================
Route::middleware('check_maintenance')->group(function () {
    Route::get('/', [GuestController::class, 'tentang'])->name('guest.tentang');
    Route::get('/katalog', [GuestController::class, 'katalog'])->name('guest.katalog');
    Route::get('/katalog/{buku}', [GuestController::class, 'detailBuku'])->name('guest.detail-buku');
    Route::get('/cek-peminjaman', [GuestController::class, 'cekPeminjaman'])->name('guest.cek-peminjaman');
});

// ================== LOGIN ADMIN ==================
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// ================== AREA ADMIN (wajib login, role super_admin ATAU staff) ==================
Route::middleware('is_admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export-pdf-bulanan', [DashboardController::class, 'exportPdfBulanan'])->name('dashboard.export-pdf-bulanan');
    Route::get('/dashboard/export-pdf-tahunan', [DashboardController::class, 'exportPdfTahunan'])->name('dashboard.export-pdf-tahunan');
    Route::get('/kalender', [KalenderController::class, 'index'])->name('kalender.index');

    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('mahasiswa/{mahasiswa}/bekukan', [MahasiswaController::class, 'bekukan'])->name('mahasiswa.bekukan');
    Route::post('mahasiswa/{mahasiswa}/aktifkan', [MahasiswaController::class, 'aktifkan'])->name('mahasiswa.aktifkan');
    Route::get('mahasiswa/{mahasiswa}/riwayat', [MahasiswaController::class, 'riwayat'])->name('mahasiswa.riwayat');

    Route::resource('buku', BukuController::class);
    Route::get('buku/{buku}/riwayat', [BukuController::class, 'riwayat'])->name('buku.riwayat');

    Route::get('peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::post('peminjaman/{peminjaman}/kembalikan', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');
    Route::post('peminjaman/{peminjaman}/perpanjang', [PeminjamanController::class, 'perpanjang'])->name('peminjaman.perpanjang');

    // ============ KHUSUS SUPER ADMIN: kelola akun admin/staff & log aktivitas ============
    Route::middleware('is_super_admin')->group(function () {
        Route::get('pengguna', [AdminUserController::class, 'index'])->name('pengguna.index');
        Route::get('pengguna/create', [AdminUserController::class, 'create'])->name('pengguna.create');
        Route::post('pengguna', [AdminUserController::class, 'store'])->name('pengguna.store');
        Route::delete('pengguna/{pengguna}', [AdminUserController::class, 'destroy'])->name('pengguna.destroy');
        Route::get('aktivitas', [AdminUserController::class, 'aktivitas'])->name('aktivitas');
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::post('pengaturan/toggle', [PengaturanController::class, 'toggle'])->name('pengaturan.toggle');
    });
});
