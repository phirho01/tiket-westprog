<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\WisataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PemesananAdminController;
use App\Http\Controllers\PemesananUserController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes - Westprog Ticket
|--------------------------------------------------------------------------
| Seluruh rute aplikasi pemesanan tiket wisata dalam Bahasa Indonesia baku.
|
*/

// Halaman Beranda Publik (Katalog Wisata)
Route::get('/', [BerandaController::class, 'indeks'])->name('beranda');

// Rute Autentikasi (Masuk, Daftar, & Lupa Kata Sandi)
Route::get('/masuk', [AuthController::class, 'tampilkanMasuk'])->name('masuk');
Route::post('/masuk', [AuthController::class, 'prosesMasuk'])->name('masuk.proses');
Route::get('/daftar', [AuthController::class, 'tampilkanDaftar'])->name('daftar');
Route::post('/daftar', [AuthController::class, 'prosesDaftar'])->name('daftar.proses');
Route::get('/lupa-password', [AuthController::class, 'tampilkanLupaPassword'])->name('lupa_password');
Route::post('/lupa-password', [AuthController::class, 'prosesLupaPassword'])->name('lupa_password.proses');
Route::match(['get', 'post'], '/keluar', [AuthController::class, 'keluar'])->name('keluar');

// Rute Terproteksi Pengguna (Wajib Login untuk Detail Wisata, Pemesanan, E-Tiket, & Batal 10 Menit)
Route::middleware(['auth'])->group(function () {
    Route::get('/wisata/{id}', [BerandaController::class, 'detailWisata'])->name('wisata.detail');
    Route::get('/user/dasbor', [BerandaController::class, 'dasborUser'])->name('user.dasbor');
    
    // Pemesanan & E-Tiket PDF
    Route::get('/user/pesan/{id_wisata}', [PemesananUserController::class, 'tampilkanPesan'])->name('user.pesan');
    Route::post('/user/pesan', [PemesananUserController::class, 'prosesPesan'])->name('user.pesan.proses');
    Route::get('/user/riwayat', [PemesananUserController::class, 'riwayat'])->name('user.riwayat');
    Route::get('/user/tiket/{id}', [PemesananUserController::class, 'lihatTiket'])->name('user.tiket.lihat');
    Route::post('/user/pesan/{id}/konfirmasi-bayar', [PemesananUserController::class, 'konfirmasiBayar'])->name('user.pesan.konfirmasi_bayar');
    Route::post('/user/pesan/{id}/ajukan-pembatalan', [PemesananUserController::class, 'ajukanPembatalan'])->name('user.pesan.batal');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/ulasan', [UlasanController::class, 'store'])->name('ulasan.store');
});

// Rute khusus Admin (Halaman Terpisah Setiap Modul)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dasbor Utama & Grafik Analisis
    Route::get('/dasbor', [AdminDashboardController::class, 'indeks'])->name('dasbor');

    // Kelola Wisata
    Route::get('/wisata', [WisataController::class, 'indeksAdmin'])->name('wisata.index');
    Route::get('/wisata/tambah', [WisataController::class, 'tambah'])->name('wisata.tambah');
    Route::post('/wisata/simpan', [WisataController::class, 'simpan'])->name('wisata.simpan');
    Route::get('/wisata/{id}/edit', [WisataController::class, 'edit'])->name('wisata.edit');
    Route::put('/wisata/{id}/perbarui', [WisataController::class, 'perbarui'])->name('wisata.perbarui');
    Route::delete('/wisata/{id}/hapus', [WisataController::class, 'hapus'])->name('wisata.hapus');

    // Kelola Pemesanan Tiket & Pembayaran
    Route::get('/pemesanan', [PemesananAdminController::class, 'index'])->name('pemesanan.index');
    Route::patch('/pemesanan/{id}/approve', [PemesananAdminController::class, 'setujui'])->name('pemesanan.setujui');
    Route::patch('/pemesanan/{id}/cancel', [PemesananAdminController::class, 'batalkan'])->name('pemesanan.batalkan');
    Route::patch('/pemesanan/{id}/status', [PemesananAdminController::class, 'perbaruiStatus'])->name('pemesanan.status');

    // Kelola Ulasan
    Route::get('/ulasan', [UlasanController::class, 'adminIndex'])->name('ulasan.index');
    Route::delete('/ulasan/{id}', [UlasanController::class, 'destroy'])->name('ulasan.destroy');

    // Kelola Pengguna (CRUD Pengguna Role 'user')
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/tambah', [UserController::class, 'tambah'])->name('users.tambah');
    Route::post('/users/simpan', [UserController::class, 'simpan'])->name('users.simpan');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}/perbarui', [UserController::class, 'perbarui'])->name('users.perbarui');
    Route::delete('/users/{id}/hapus', [UserController::class, 'hapus'])->name('users.hapus');
});
