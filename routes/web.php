<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesainController;

// ===============================================
// ROUTE AUTENTIKASI (FIXED)
// ===============================================

// LOGIN GET: Menampilkan form login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

// LOGIN POST: Memproses login.
// PERBAIKAN: Mengubah doLogin menjadi authenticate dan login.process menjadi login.post
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

// LOGOUT POST: Memproses logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================================
// ROUTE DESAIN & REDIRECT
// ===============================================

// REDIRECT UTAMA
Route::get('/', function () {
    // Pastikan route 'desain.dashboard' sudah terdefinisi dan dapat diakses
    return redirect()->route('desain.dashboard');
});

// GROUP DESAIN (DILINDUNGI AUTH)
Route::prefix('desain')->name('desain.')->middleware('auth')->group(function () {

    Route::get('/dashboard', [DesainController::class, 'dashboard'])->name('dashboard');
    
    // Di layout Anda, nama route ini adalah 'desain.kelola'
    Route::get('/kelola-desain', [DesainController::class, 'kelolaDesain'])->name('kelola');
    
    // Tambahkan route untuk Template Desain agar cocok dengan nama 'desain.pengaturan' di layout.
    // Di layout, Anda memanggil route 'desain.pengaturan' untuk Template Desain.
    Route::get('/pengaturan', [DesainController::class, 'pengaturan'])->name('pengaturan');

    // Tambahkan route 'desain.riwayat' 
    Route::get('/riwayat', [DesainController::class, 'riwayat'])->name('riwayat');

    // Jika Anda memiliki route lain yang perlu diakses, misalnya revisi:
    Route::get('/revisi', [DesainController::class, 'revisions'])->name('revisions'); 
});