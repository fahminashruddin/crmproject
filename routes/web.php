<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DesainController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ManajemenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AnalitikController;
use App\Http\Controllers\ManajemenExportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === 1. ROUTE UNTUK TAMU (BELUM LOGIN) ===
Route::middleware(['guest'])->group(function () {

    Route::get('/', function () {
    return redirect()->route('login');
});

    // Login Umum
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');

    // Login Khusus per Role (Optional, diarahkan ke controller yg sama)
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

// === 2. HELPER FUNCTION UNTUK REDIRECT ===
if (!function_exists('checkRoleRedirect')) {
    function checkRoleRedirect($roleName) {
        // Cek apakah tabel roles ada & role tersebut ada
        try {
            $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', [$roleName])->first();
        } catch (\Exception $e) {
            // Fallback jika tabel roles belum ada/migrasi belum jalan
            return redirect()->route('login');
        }

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
Route::get('/admin', function () { return checkRoleRedirect('admin'); });
Route::get('/desain', function () { return checkRoleRedirect('desain'); });
Route::get('/produksi', function () { return checkRoleRedirect('produksi'); });
Route::get('/manajemen', function () { return checkRoleRedirect('manajemen'); });


// === 4. ROUTE UNTUK MEMBER (SUDAH LOGIN) ===
Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard Umum
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- A. AREA ADMIN ---
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('orders', [AdminController::class, 'orders'])->name('orders');
        Route::post('orders', [AdminController::class, 'storeOrder'])->name('orders.store');
        Route::patch('orders/{id}/update', [AdminController::class, 'updateOrder'])->name('orders.update');
        Route::get('payments', [AdminController::class, 'payments'])->name('payments');
        Route::post('payments/{id}/verify', [AdminController::class, 'verifyPayment'])->name('payments.verify');
        Route::post('payments/{id}/reject', [AdminController::class, 'rejectPayment'])->name('payments.reject');
        Route::get('users', [AdminController::class, 'users'])->name('users');
        Route::post('users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::delete('users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        Route::patch('users/{id}/toggle', [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
        Route::get('settings', [AdminController::class, 'settings'])->name('settings');
        Route::get('notifications', [AdminController::class, 'notifications'])->name('notifications');
    });

    // --- B. AREA DESAIN ---
    Route::prefix('desain')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DesainController::class, 'dashboard'])->name('desain.dashboard');
    Route::get('/kelola', [DesainController::class, 'kelolaDesain'])->name('desain.kelola');
    Route::get('/designs', [DesainController::class, 'kelolaDesain'])->name('desain.designs');
    Route::get('/revisions', [DesainController::class, 'revisions'])->name('desain.revisions');
    Route::get('/riwayat', [DesainController::class, 'riwayat'])->name('desain.riwayat');
    Route::get('/template', [DesainController::class, 'pengaturan'])->name('desain.template');
    Route::post('/upload', [DesainController::class, 'upload'])->name('desain.upload');
    Route::post('/revisi', [DesainController::class, 'revisi'])->name('desain.revisi');
    Route::post('/setujui', [DesainController::class, 'setujui'])->name('desain.setujui');
    });

    // --- C. AREA PRODUKSI (LENGKAP DENGAN FITUR BARU) ---
    Route::prefix('produksi')->name('produksi.')->group(function () {
        // 1. Dashboard & List
        Route::get('dashboard', [ProduksiController::class, 'dashboard'])->name('dashboard');
        Route::get('productions', [ProduksiController::class, 'productions'])->name('productions');

        // 2. Aksi Tombol (Start & Complete)
        Route::post('start/{id}', [ProduksiController::class, 'startProduction'])->name('productions.start');
        Route::post('complete/{id}', [ProduksiController::class, 'completeProduction'])->name('productions.complete');

        // 3. Kendala (Issues)
        Route::get('issues', [ProduksiController::class, 'issues'])->name('issues');
        Route::post('issues', [ProduksiController::class, 'storeIssue'])->name('issues.store');

        // 4. Print Job Sheet
        Route::get('print/{id}', [ProduksiController::class, 'printJobSheet'])->name('print');
    });

    // --- D. AREA MANAJEMEN ---
    Route::prefix('manajemen')->name('manajemen.')->group(function () {

        // 1. Dashboard Utama
        Route::get('dashboard', [ManajemenController::class, 'dashboard'])->name('dashboard');

        // 2. Laporan (Menggunakan LaporanController)
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('laporan/export', [LaporanController::class, 'export'])->name('laporan.export');

        // 3. Analitik (Menggunakan AnalitikController)
        Route::get('analytics', [AnalitikController::class, 'index'])->name('analytics');

        // 4. Export Data Center (Menggunakan ManajemenExportController)
        Route::get('export', [ManajemenExportController::class, 'index'])->name('export');

        // Sub-menu Export
        Route::get('export/pesanan', [ManajemenExportController::class, 'exportPesanan'])->name('export.pesanan');
        Route::get('export/pelanggan', [ManajemenExportController::class, 'exportPelanggan'])->name('export.pelanggan');
        Route::get('export/keuangan', [ManajemenExportController::class, 'exportKeuangan'])->name('export.keuangan');
            Route::get('export/produksi', [ManajemenExportController::class, 'exportProduksi'])->name('export.produksi');
        });
    });
