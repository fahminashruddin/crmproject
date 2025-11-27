<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
// Tambahkan DB untuk pengecekan peran (role)
use Illuminate\Support\Facades\DB; 

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin()
    {
        // Menampilkan view login.blade.php.
        // Asumsi: File view berada di resources/views/login.blade.php.
        return view('login'); 
    }

    // 2. Proses Login dengan Pengalihan Berbasis Peran
    public function authenticate(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Ambil user yang sedang login
            $user = Auth::user();

            // Ambil user yang sedang login
            $user = Auth::user();

            // Jika login via role-specific page, pastikan akun punya role yang sesuai
            if ($roleRecord && $user->role_id != $roleRecord->id) {
                // logout dan kembalikan error
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses ke area ' . $roleRecord->nama_role . '.',
                ])->onlyInput('email');
            }

            // === LOGIC REDIRECT BARU ===
            // Ambil nama role user dari database berdasarkan role_id
            $userRole = DB::table('roles')->where('id', $user->role_id)->first();

            if ($userRole) {
                $roleName = strtolower($userRole->nama_role);

                // Arahkan ke dashboard sesuai role
                switch ($roleName) {
                    case 'produksi':
                        return redirect()->intended(route('produksi.dashboard'));
                    case 'desain':
                        return redirect()->intended(route('desain.dashboard'));
                    case 'manajemen':
                        return redirect()->intended(route('manajemen.dashboard'));
                    case 'admin':
                        return redirect()->intended(route('admin.dashboard'));
                }
            }

            // Default fallback (jika role tidak dikenali atau user biasa)
            return redirect()->intended(route('dashboard'));
        }

        // Login gagal -> Kembali dengan error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // 3. Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}