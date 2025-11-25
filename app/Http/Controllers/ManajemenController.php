<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ManajemenController extends Controller
{
    // Ensure user is authenticated and has Manajemen role
    protected function ensureManajemen()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['manajemen'])->value('id');
        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized. Only Manajemen role can access this.');
        }
    }

    public function dashboard()
    {
        $this->ensureManajemen();

        return view('manajemen.dashboard', [
            'totalPesanan' => 0,
            'totalPendapatan' => 0,
            'tingkatKepuasan' => 0,
        ]);
    }

    public function reports()
    {
        $this->ensureManajemen();

        return view('manajemen.reports', [
            'reports' => [],
        ]);
    }

    public function analytics()
    {
        $this->ensureManajemen();

        return view('manajemen.analytics', [
            'analytics' => [],
        ]);
    }
}
