@extends('admin.layout')

@section('title', 'Manajemen User')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Manajemen User</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pengguna sistem</p>
    </div>

    <div class="space-y-4">
        @forelse($users as $u)
            <div class="flex items-center justify-between border rounded-lg p-4 bg-white">
                <div>
                    <div class="text-sm font-medium">{{ $u->name }}</div>
                    <div class="text-xs text-gray-500">{{ $u->email }}</div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">{{ $u->nama_role ?? '—' }}</span>
                </div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Belum ada pengguna.</div>
        @endforelse
    </div>

@endsection
