@extends('layouts.admin')

@section('content')
    <div class="mb-8 animate-in fade-in slide-in-from-bottom-4 duration-500">
        <h1 class="text-3xl font-bold text-slate-900">Dashboard Umum</h1>
        <p class="text-slate-500 mt-1">Selamat datang kembali, {{ Auth::user()->name }}!</p>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 flex items-start gap-4 mb-8">
        <div class="p-2 bg-blue-100 rounded-full text-blue-600 flex-shrink-0">
            <i data-lucide="info" class="h-5 w-5"></i>
        </div>
        <div>
            <h3 class="font-bold text-blue-900 text-lg">Informasi Akun</h3>
            <p class="text-blue-700 mt-1 text-sm leading-relaxed">
                Anda saat ini berada di <strong>Dashboard Umum</strong>.
                Role Anda terdeteksi sebagai:
                <span class="font-bold uppercase">{{ Auth::user()->role->nama_role ?? 'Member' }}</span>.
            </p>
            @if(strtolower(Auth::user()->role->nama_role ?? '') == 'admin')
                <div class="mt-4">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                        Ke Dashboard Admin <i data-lucide="arrow-right" class="ml-2 h-4 w-4"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Status Akun</span>
                <div class="p-2 bg-green-50 rounded-lg">
                    <i data-lucide="check-circle" class="h-4 w-4 text-green-600"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-slate-900">Aktif</div>
            <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <span class="text-sm font-medium text-slate-500">Bergabung Sejak</span>
                <div class="p-2 bg-purple-50 rounded-lg">
                    <i data-lucide="calendar" class="h-4 w-4 text-purple-600"></i>
                </div>
            </div>
            <div class="text-xl font-bold text-slate-900">
                {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('d M Y') }}
            </div>
            <div class="text-xs text-slate-500">Member Setia</div>
        </div>
    </div>
@endsection
