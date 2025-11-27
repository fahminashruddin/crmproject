<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ManajemenController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === 1. ROUTE UNTUK TAMU (BELUM LOGIN) ===
Route::middleware(['guest'])->group(function () {
    
    // Login Umum
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

    // Login Khusus Admin
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'admin');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'admin');
    });

    // Login Khusus Desain
    Route::prefix('desain')->name('desain.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'desain');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'desain');
    });

    // Login Khusus Produksi
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'produksi');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'produksi');
    });

    // Login Khusus Manajemen
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'manajemen');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'manajemen');
    });
});

// === 2. HELPER FUNCTION UNTUK REDIRECT ===
// Fungsi ini diletakkan di luar grup route agar bisa dipanggil
if (!function_exists('checkRoleRedirect')) {
    function checkRoleRedirect($roleName) {
        $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', [$roleName])->first();
        if (!$role) abort(404);

        if (Auth::check()) {
            if (Auth::user()->role_id == $role->id) {
                return redirect()->route($roleName . '.dashboard');
            }
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
        return redirect()->route($roleName . '.login');
    }
}

// === 3. SHORTCUT REDIRECT ===
Route::get('/admin', function () {
    return checkRoleRedirect('admin');
});
Route::get('/desain', function () {
    return checkRoleRedirect('desain');
});
Route::get('/produksi', function () {
    return checkRoleRedirect('produksi');
});
Route::get('/manajemen', function () {
    return checkRoleRedirect('manajemen');
});


// === 4. ROUTE UNTUK MEMBER (SUDAH LOGIN) ===
Route::middleware(['auth'])->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Umum (Fallback)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- A. AREA ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    });

    // --- B. AREA DESAIN ---
    Route::prefix('desain')->name('desain.')->group(function () {
        Route::get('dashboard', [DesainController::class, 'dashboard'])->name('dashboard');
        Route::get('designs', [DesainController::class, 'designs'])->name('designs');
        Route::get('revisions', [DesainController::class, 'revisions'])->name('revisions');
    });

    // --- C. AREA PRODUKSI ---
    Route::prefix('produksi')->name('produksi.')->group(function () {
        // Dashboard Utama
        Route::get('dashboard', [ProduksiController::class, 'dashboard'])->name('dashboard');
        
        // List Antrian
        Route::get('productions', [ProduksiController::class, 'productions'])->name('productions');
        
        // Aksi Tombol (POST)
        Route::post('productions/{id}/start', [ProduksiController::class, 'startProduction'])->name('productions.start');
        Route::post('productions/{id}/complete', [ProduksiController::class, 'completeProduction'])->name('productions.complete');
        
        // Halaman Kendala
        Route::get('issues', [ProduksiController::class, 'issues'])->name('issues');
    });

    // --- D. AREA MANAJEMEN ---
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('dashboard', [ManajemenController::class, 'dashboard'])->name('dashboard');
        Route::get('reports', [ManajemenController::class, 'reports'])->name('reports');
        Route::get('analytics', [ManajemenController::class, 'analytics'])->name('analytics');
    });
});