<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin(Request $request)
    {
        // Mendapatkan parameter role dari route
        $roleSlug = $request->route('role');

        // Menampilkan view login
        return view('login', ['role' => $roleSlug]);
    }

    // 2. Proses Login dengan Pengalihan Berbasis Peran
    public function authenticate(Request $request)
    {
        // --- PERBAIKAN 1: TAMBAHKAN LOGIKA ROLE RECORD ---
        $roleSlug = $request->route('role');
        $roleRecord = null; // Inisialisasi

        if ($roleSlug) {
            $roleRecord = DB::table('roles')
                ->whereRaw('LOWER(nama_role) = ?', [strtolower($roleSlug)])
                ->first();
        }
        // ------------------------------------------------

        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil user yang sedang login (Hanya satu kali)
            $user = Auth::user();

            // Pengecekan Akses Halaman Spesifik (Jika login dari /admin/login)
            if ($roleRecord && $user->role_id != $roleRecord->id) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses ke area ' . ucfirst($roleSlug) . '.',
                ])->onlyInput('email');
            }

            // === LOGIC REDIRECT BERDASARKAN ROLE ===
            $userRole = DB::table('roles')->where('id', $user->role_id)->first();

            if ($userRole) {
                // Bersihkan spasi dan ubah ke huruf kecil
                $roleName = strtolower(trim($userRole->nama_role));

                // Arahkan ke dashboard sesuai role
                switch ($roleName) {
                    // PERBAIKAN 3: HAPUS intended() AGAR REDIRECT BERSIFAT PAKSA
                    case 'produksi':
                        return redirect()->route('produksi.dashboard');
                    case 'desain':
                        return redirect()->route('desain.dashboard');
                    case 'manajemen':
                        return redirect()->route('manajemen.dashboard');
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                }
            }

            // Default fallback (Jika role tidak dikenali)
            // Gunakan route() paksa jika Anda tidak ingin user tersasar ke URL lama
            return redirect()->route('dashboard');
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
