<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role->nama_role) {
            'admin'    => redirect()->route('admin.dashboard'),
            'desain'   => redirect()->route('desain.dashboard'),
            'produksi' => redirect()->route('produksi.dashboard'),
            'manajemen'=> redirect()->route('manajemen.dashboard'),
            default    => redirect()->route('login'),
        };
    }
}
