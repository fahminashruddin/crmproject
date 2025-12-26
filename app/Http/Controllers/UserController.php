<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Pengguna; 
use App\Models\Role;

class UserController extends Controller
{


    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Proses Auth
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Ambil User yang sedang login (Menggunakan Model)
            $user = Auth::user();

            // 3. Redirect Berdasarkan Role
            // Pastikan di Model Pengguna ada relasi: public function role() { ... }
            if ($user->role) {
                $roleName = strtolower(trim($user->role->nama_role));

                switch ($roleName) {
                    case 'produksi':
                        return redirect()->route('produksi.dashboard');
                    case 'desain':
                        return redirect()->route('desain.dashboard');
                    case 'manajemen':
                        return redirect()->route('manajemen.dashboard');
                    case 'admin':
                        return redirect()->route('admin.dashboard');
                    default:
                        return redirect()->route('dashboard');
                }
            }

            return redirect()->route('dashboard');
        }

        // Login Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }



    public function changePassword(Request $request, $id)
    {
        $request->validate(['password' => 'required|min:6']);

        // Update menggunakan Model
        $user = Pengguna::findOrFail($id);

        $user->update([
            'password' => Hash::make($request->password)
        ]);
        // Updated_at otomatis diurus Eloquent

        return back()->with('success', 'Password berhasil diperbarui.');
    }


    public function index()
    {
        // Ganti Query Manual dengan Eager Loading
        // Mengambil semua pengguna beserta role-nya
        $users = Pengguna::with('role')->orderBy('id', 'asc')->get();

        // Ambil semua role untuk dropdown di view
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:penggunas,username',
            'email'    => 'required|email|unique:penggunas,email',
            'password' => 'required|string|min:6',
            'role_id'  => 'required|exists:roles,id',
        ]);

        // Create User Baru via Model
        Pengguna::create([
            'name'     => $request->name,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'User baru berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        if ($id == Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }

        // Hapus via Model
        $user = Pengguna::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
