<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()https://github.com/fahminashruddin/crmproject/pull/7/conflict?name=routes%252Fweb.php&ancestor_oid=3f5402bc381d7ca088be8e8fa29128b36d330eaf&base_oid=ce6843252ddd944a07ac11b60079921489696561&head_oid=e86005b4d0c3a7736a05070639eca7b3a2cba431
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
