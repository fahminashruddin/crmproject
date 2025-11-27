<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesainController; // Pastikan import ini ada
use App\Http\Controllers\ProduksiController; // Pastikan import ini ada
use App\Http\Controllers\ManajemenController; // Pastikan import ini ada
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// ====================================================
// 1. GUEST AREA (Login Routes)
// ====================================================
Route::middleware(['guest', \App\Http\Middleware\SessionTimeout::class])->group(function () {
    // Generic login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

    // Role-based login routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login')->defaults('role', 'admin');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'admin');
    });

    // Login Khusus Desain
    Route::prefix('desain')->name('desain.')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login')->defaults('role', 'desain');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'desain');
    });

    // Login Khusus Produksi
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login')->defaults('role', 'produksi');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'produksi');
    });

    // Login Khusus Manajemen
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login')->defaults('role', 'manajemen');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'manajemen');
    });
});

// ====================================================
// 2. SHORTCUT REDIRECTS (FIXED)
// ====================================================

// --- ADMIN ---
Route::get('/admin', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['admin'])->first();
    if (!$role) abort(404);

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            return redirect()->route('admin.dashboard'); // SUDAH BENAR
        }
        abort(403, 'Akses ditolak.');
    }
    return redirect()->route('admin.login');
});

// --- DESAIN ---
Route::get('/desain', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['desain'])->first();
    if (!$role) abort(404);

    if (Auth::check()) {
        if (Auth::user()->user_role_id == $role->id || Auth::user()->role_id == $role->id) {
            // PERBAIKAN: Redirect spesifik ke desain dashboard
            return redirect()->route('desain.dashboard');
        }
        abort(403, 'Akses ditolak.');
    }
    return redirect()->route('desain.login');
});

// --- PRODUKSI ---
Route::get('/produksi', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->first();
    if (!$role) abort(404);

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            // PERBAIKAN: Redirect spesifik ke produksi dashboard
            return redirect()->route('produksi.dashboard');
        }
        abort(403, 'Akses ditolak.');
    }
    return redirect()->route('produksi.login');
});

// --- MANAJEMEN ---
Route::get('/manajemen', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['manajemen'])->first();
    if (!$role) abort(404);

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            // PERBAIKAN: Redirect spesifik ke manajemen dashboard
            return redirect()->route('manajemen.dashboard');
        }
        abort(403, 'Akses ditolak.');
    }
    return redirect()->route('manajemen.login');
});

// ====================================================
// 3. PROTECTED AREA (Dashboards)
// ====================================================
Route::middleware(['auth', \App\Http\Middleware\SessionTimeout::class])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Umum (Fallback)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- A. AREA ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        // Ini akan memanggil view 'admin.dashboard' via Controller
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('orders', [AdminController::class, 'orders'])->name('orders');
        Route::post('orders', [AdminController::class, 'storeOrder'])->name('orders.store');
        Route::patch('orders/{id}/update-status', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
        Route::get('payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    });

    // --- B. AREA DESAIN ---
    Route::prefix('desain')->name('desain.')->group(function () {
        Route::get('dashboard', [DesainController::class, 'dashboard'])->name('dashboard');
        Route::get('designs', [DesainController::class, 'kelolaDesain'])->name('kelola');
        Route::get('history', [DesainController::class, 'history'])->name('riwayat');
        Route::get('revisions', [DesainController::class, 'revisions'])->name('pengaturan');
    });

    // --- PRODUKSI AREA (Yang Sebelumnya Hilang) ---
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('dashboard', [ProduksiController::class, 'dashboard'])->name('dashboard');
        Route::get('productions', [ProduksiController::class, 'productions'])->name('productions');
        Route::get('issues', [ProduksiController::class, 'issues'])->name('issues');
    });

    // --- MANAJEMEN AREA (Yang Sebelumnya Hilang) ---
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('dashboard', [ManajemenController::class, 'dashboard'])->name('dashboard');
        Route::get('reports', [ManajemenController::class, 'reports'])->name('reports');
        Route::get('analytics', [ManajemenController::class, 'analytics'])->name('analytics');
    });

});
