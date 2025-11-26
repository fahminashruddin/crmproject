<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Route untuk Tamu (Belum Login)
Route::middleware([ 'guest', \App\Http\Middleware\SessionTimeout::class ])->group(function () {
    // Generic login (keberadaan tetap dipertahankan)
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

    // Role-based login routes (admin, desain, produksi, manajemen)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'admin');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'admin');
    });

    Route::prefix('desain')->name('desain.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'desain');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'desain');
    });

    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'produksi');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'produksi');
    });

    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('login', [AuthController::class, 'index'])->name('login')->defaults('role', 'manajemen');
        Route::post('login', [AuthController::class, 'authenticate'])->name('login.post')->defaults('role', 'manajemen');
    });
});

// Shortcuts for role root paths: /admin, /desain, /produksi, /manajemen
// - If guest -> redirect to role login
// - If authenticated and role matches -> redirect to dashboard
// - If authenticated but role mismatch -> 403
Route::get('/admin', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['admin'])->first();
    if (! $role) {
        abort(404);
    }

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            return redirect('/dashboard');
        }
        abort(403);
    }

    return redirect()->route('admin.login');
});

Route::get('/desain', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['desain'])->first();
    if (! $role) {
        abort(404);
    }

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            return redirect('/dashboard');
        }
        abort(403);
    }

    return redirect()->route('desain.login');
});

Route::get('/produksi', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->first();
    if (! $role) {
        abort(404);
    }

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            return redirect('/dashboard');
        }
        abort(403);
    }

    return redirect()->route('produksi.login');
});

Route::get('/manajemen', function () {
    $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['manajemen'])->first();
    if (! $role) {
        abort(404);
    }

    if (Auth::check()) {
        if (Auth::user()->role_id == $role->id) {
            return redirect('/dashboard');
        }
        abort(403);
    }

    return redirect()->route('manajemen.login');
});

// Route untuk Member (Sudah Login)
Route::middleware([ 'auth', \App\Http\Middleware\SessionTimeout::class ])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Route Dashboard yang sudah kita buat sebelumnya
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin area routes (only for authenticated admin users)
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('payments', [AdminController::class, 'payments'])->name('payments');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    });
});
