@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
    {{-- x-data untuk mengontrol modal/form tambah user --}}
    <div class="p-8" x-data="{ showForm: false }">

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Manajemen User</h1>
                <p class="text-slate-500 mt-1">Kelola pengguna dan hak akses sistem</p>
            </div>

            <button @click="showForm = !showForm"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg flex items-center gap-2 text-sm font-medium transition shadow-sm">
                <i data-lucide="plus" x-show="!showForm" class="w-4 h-4"></i>
                <i data-lucide="x" x-show="showForm" class="w-4 h-4" style="display: none;"></i>
                <span x-text="showForm ? 'Tutup Form' : 'Tambah User'"></span>
            </button>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-2 text-sm animate-in fade-in">
                <i data-lucide="check-circle" class="h-5 w-5"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg flex items-center gap-2 text-sm animate-in fade-in">
                <i data-lucide="alert-circle" class="h-5 w-5"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div x-show="showForm"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform -translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white border border-gray-200 rounded-xl p-6 mb-8 shadow-sm"
             style="display: none;">

            <div class="mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-lg font-bold text-slate-900">Form User Baru</h2>
                <p class="text-sm text-slate-500">Isi data di bawah untuk menambahkan pengguna baru</p>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none" placeholder="Contoh: Administrator">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Username</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400 text-sm">@</span>
                            <input type="text" name="username" required class="w-full pl-8 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none" placeholder="admin_utama">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none" placeholder="email@percetakan.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1">Role (Hak Akses)</label>
                        <div class="relative">
                            <select name="role_id" required class="w-full px-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-slate-900 focus:outline-none bg-white appearance-none">
                                <option value="">Pilih Role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nama_role }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                <i data-lucide="chevron-down" class="h-4 w-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showForm = false" class="px-6 py-2 border border-gray-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>

        <div class="space-y-6 mb-10">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Daftar Pengguna</h2>
                <p class="text-slate-500 text-sm mt-1">Kelola semua pengguna sistem</p>
            </div>

            <div class="space-y-4">
                @forelse($users as $user)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 bg-white border border-gray-200 rounded-xl shadow-sm hover:border-gray-300 transition-all">

                    <div class="mb-4 sm:mb-0">
                        <h3 class="font-bold text-slate-900 text-base">{{ $user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $user->email }}</p>
                        <p class="text-xs text-slate-400 mt-1">Dibuat: {{ \Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }}</p>
                    </div>

                    <div class="flex items-center gap-3">

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-900 text-white">
                            Aktif
                        </span>

                        <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold border border-gray-200 text-slate-700 bg-white capitalize">
                            {{ $user->role->nama_role }}
                        </span>

                        <button class="px-4 py-1.5 text-xs font-medium text-slate-700 bg-white border border-gray-200 rounded-md hover:bg-gray-50 transition">
                            Nonaktifkan
                        </button>

                        @if(Auth::id() != $user->id)
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin hapus user ini?');" class="inline-flex">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 border border-gray-200 rounded-md transition" title="Hapus">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        @else
                             <div class="w-8 h-8"></div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-xl">
                    <i data-lucide="users" class="mx-auto h-10 w-10 text-slate-300 mb-3"></i>
                    <p class="text-slate-500">Belum ada data pengguna.</p>
                </div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-slate-900">Informasi Credential</h2>
                <p class="text-sm text-slate-500">Credential untuk testing sistem</p>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($users as $user)
                <div class="p-5 border border-gray-200 rounded-xl hover:shadow-md transition-shadow bg-white relative">

                    <span class="absolute top-4 right-4 px-2 py-1 text-[10px] font-bold border border-gray-200 rounded-full text-slate-600 bg-white uppercase tracking-wide">
                        {{ $user->role->nama_role }}
                    </span>

                    <div class="mb-3 pr-16"> <h3 class="font-bold text-slate-900">{{ $user->name }}</h3>
                    </div>

                    <div class="space-y-1 text-sm">
                        <p>
                            <span class="font-bold text-slate-900">Email:</span>
                            <span class="text-slate-600">{{ $user->email }}</span>
                        </p>
                        <p>
                            <span class="font-bold text-slate-900">Password:</span>
                            <span class="text-slate-600 font-mono">******</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
