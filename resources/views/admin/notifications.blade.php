@extends('admin.layout')

@section('title', 'Notifikasi')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold">Notifikasi</h1>
        <p class="text-sm text-gray-500 mt-1">Notifikasi sistem</p>
    </div>

    <div class="space-y-4">
        @forelse($notifications as $n)
            <div class="border rounded-lg p-4 bg-white">
                <div class="text-sm">{{ $n }}</div>
            </div>
        @empty
            <div class="text-sm text-gray-500">Belum ada notifikasi.</div>
        @endforelse
    </div>

@endsection
