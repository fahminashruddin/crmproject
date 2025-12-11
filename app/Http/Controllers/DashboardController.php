<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard (placeholder).
     */
    public function index()
    {
        $user = Auth::user();

        // Ambil nama role user
        $role = DB::table('roles')->where('id', $user->role_id)->value('nama_role');

        // Normalisasi nama role (lowercase, trim)
        $roleName = strtolower(trim($role));

        // Redirect ke dashboard spesifik berdasarkan role
        switch ($roleName) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'produksi':
                return redirect()->route('produksi.dashboard');
            case 'desain':
                return redirect()->route('desain.dashboard');
            case 'manajemen':
                return redirect()->route('manajemen.dashboard');
            default:
                // Fallback jika role tidak dikenali, logout atau tampilkan error
                Auth::logout();
                return redirect()->route('login')->withErrors(['email' => 'Role tidak dikenali.']);
        }
    }
}
