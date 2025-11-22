<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login (support role-aware login)
    public function index(Request $request)
    {
        $roleSlug = $request->route('role');

        $roleRecord = null;
        if ($roleSlug) {
            // cari role dengan case-insensitive match pada kolom nama_role
            $roleRecord = DB::table('roles')
                ->whereRaw('LOWER(nama_role) = ?', [strtolower($roleSlug)])
                ->first();

            if (! $roleRecord) {
                abort(404);
            }
        }

        // Nama route POST untuk login bergantung pada role (contoh: admin.login.post)
        $loginPostRoute = $roleSlug ? ($roleSlug . '.login.post') : 'login.post';

        return view('login', [
            'role' => $roleSlug,
            'roleRecord' => $roleRecord,
            'loginPostRoute' => $loginPostRoute,
        ]);
    }

    // 2. Proses Login
    public function authenticate(Request $request)
    {
        $roleSlug = $request->route('role');

        $roleRecord = null;
        if ($roleSlug) {
            $roleRecord = DB::table('roles')
                ->whereRaw('LOWER(nama_role) = ?', [strtolower($roleSlug)])
                ->first();

            if (! $roleRecord) {
                abort(404);
            }
        }

        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba login (Auth::attempt otomatis mengecek hash password)
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Jika login via role-specific page, pastikan akun punya role yang sesuai
            if ($roleRecord && Auth::user()->role_id != $roleRecord->id) {
                // logout dan kembalikan error
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun ini tidak memiliki akses ke area ' . $roleRecord->nama_role . '.',
                ])->onlyInput('email');
            }

            // Login sukses -> Arahkan ke Dashboard
            return redirect()->intended('dashboard');
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
