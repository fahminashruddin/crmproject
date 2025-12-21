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
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AnalitikController;
use App\Http\Controllers\ManajemenExportController;

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
        $role = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', bindings: [$roleName])->first();
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
    });

    // --- C. AREA PRODUKSI ---
    Route::prefix('produksi')->name('produksi.')->group(function () {
        Route::get('dashboard', [ProduksiController::class, 'dashboard'])->name('dashboard');
        Route::get('productions', [ProduksiController::class, 'productions'])->name('productions');

        // Aksi Tombol (POST)
        Route::post('productions/{id}/start', [ProduksiController::class, 'startProduction'])->name('productions.start');
        Route::post('productions/{id}/complete', [ProduksiController::class, 'completeProduction'])->name('productions.complete');

        // Halaman Kendala
        Route::get('issues', [ProduksiController::class, 'issues'])->name('issues');
        Route::post('issues/store', [ProduksiController::class, 'storeIssue'])->name('issues.store');

        Route::get('print/{id}', [ProduksiController::class, 'printJobSheet'])->name('print');
    });

    // --- D. AREA MANAJEMEN ---
    Route::prefix('manajemen')->name('manajemen.')->group(function () {
        Route::get('dashboard', [ManajemenController::class, 'dashboard'])->name('dashboard');
    });


    Route::middleware(['auth'])->group(function () {
        Route::get('/manajemen/laporan', [LaporanController::class, 'index'])->name('manajemen.laporan.index');

    Route::get('/manajemen/laporan/export', [LaporanController::class, 'export'])
            ->name('manajemen.laporan.export');
        });

    Route::middleware(['auth'])->group(function () {
    Route::get('/manajemen/analytics', [AnalitikController::class, 'index'])
        ->name('manajemen.analytics');
    });


    // EXPORT DATA
    Route::get('/manajemen/export', [ManajemenExportController::class, 'index'])
        ->name('manajemen.export');

    // Export Semua Pesanan
    Route::get('/manajemen/export/pesanan', [ManajemenExportController::class, 'exportPesanan'])
        ->name('manajemen.export.pesanan');

    // Export Data Pelanggan
    Route::get('/manajemen/export/pelanggan', [ManajemenExportController::class, 'exportPelanggan'])
        ->name('manajemen.export.pelanggan');

    // Export Laporan Keuangan
    Route::get('/manajemen/export/keuangan', [ManajemenExportController::class, 'exportKeuangan'])
        ->name('manajemen.export.keuangan');

    // Export Laporan Produksi
    Route::get('/manajemen/export/produksi', [ManajemenExportController::class, 'exportProduksi'])
        ->name('manajemen.export.produksi');


});
