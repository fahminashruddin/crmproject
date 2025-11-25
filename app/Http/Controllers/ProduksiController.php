<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProduksiController extends Controller
{
    // Ensure user is authenticated and has Produksi role
    protected function ensureProduksi()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['produksi'])->value('id');
        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized. Only Produksi role can access this.');
        }
    }

    public function dashboard()
    {
        $this->ensureProduksi();

        return view('produksi.dashboard', [
            'produksiMenunggu' => 0,
            'produksiSelesai' => 0,
            'targetHarian' => 0,
        ]);
    }

    public function productions()
    {
        $this->ensureProduksi();

        return view('produksi.productions', [
            'productions' => [],
        ]);
    }

    public function issues()
    {
        $this->ensureProduksi();

        return view('produksi.issues', [
            'issues' => [],
        ]);
    }
}
