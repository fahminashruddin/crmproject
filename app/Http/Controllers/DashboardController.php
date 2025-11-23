<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard (placeholder).
     */
    public function index()
    {
        // Jika belum ada view dashboard, kembalikan teks sederhana.
        if (view()->exists('dashboard')) {
            return view('dashboard');
        }

        return response('Dashboard (placeholder) - buat view `resources/views/dashboard.blade.php` jika diperlukan.', 200);
    }
}
