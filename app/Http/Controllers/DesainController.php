<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DesainController extends Controller
{
    // Ensure user is authenticated and has Desain role
    protected function ensureDesain()
    {
        $roleId = DB::table('roles')->whereRaw('LOWER(nama_role) = ?', ['desain'])->value('id');
        if (! Auth::check() || Auth::user()->role_id != $roleId) {
            abort(403, 'Unauthorized. Only Desain role can access this.');
        }
    }

    public function dashboard()
    {
        $this->ensureDesain();

        return view('desain.dashboard', [
            'desainMenunggu' => 0,
            'desainSelesai' => 0,
            'revisiMenunggu' => 0,
        ]);
    }

    public function designs()
    {
        $this->ensureDesain();

        return view('desain.designs', [
            'designs' => [],
        ]);
    }

    public function revisions()
    {
        $this->ensureDesain();

        return view('desain.revisions', [
            'revisions' => [],
        ]);
    }
}
