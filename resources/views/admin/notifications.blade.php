@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
    <div class="p-8">

        <div class="flex justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">Notifikasi</h1>
                <p class="text-slate-500 mt-1">Kelola notifikasi sistem</p>
            </div>

            {{-- Tambahan Tombol "Tandai Dibaca" (Supaya fitur route yg tadi dibuat terpakai) --}}
            @if(auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.read') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium px-4 py-2 bg-blue-50 rounded-lg transition">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-slate-900">Notifikasi Terbaru</h2>
            </div>

            <div class="p-6 space-y-4">
                @forelse($notifications as $notif)

                {{-- Persiapan Variabel biar kodingan di bawah rapi --}}
                @php
                    $data = $notif->data; // Ambil data JSON
                    $isRead = $notif->read_at !== null; // Cek status baca
                    $type = $data['type'] ?? 'info';
                @endphp

                {{-- Container: Logika opacity persis punya kamu --}}
                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-slate-50 transition-colors {{ $isRead ? 'opacity-75 bg-gray-50' : 'bg-white' }}">

                    <div class="mr-4 flex-shrink-0">
                        @if($type == 'order')
                            <div class="p-2 bg-blue-50 rounded-full text-blue-600">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                            </div>
                        @elseif($type == 'payment')
                            <div class="p-2 bg-green-50 rounded-full text-green-600">
                                <i data-lucide="check-circle" class="h-5 w-5"></i>
                            </div>
                        @else
                            <div class="p-2 bg-gray-50 rounded-full text-gray-600">
                                <i data-lucide="info" class="h-5 w-5"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-slate-900">
                            {{-- Panggil Title dari Data JSON --}}
                            {{ $data['title'] ?? 'Info' }}

                            @if(!$isRead)
                                <span class="ml-2 inline-block w-2 h-2 bg-red-500 rounded-full" title="Belum dibaca"></span>
                            @endif
                        </h3>
                        {{-- Panggil Message dari Data JSON --}}
                        <p class="text-sm text-slate-500 mt-0.5">{{ $data['message'] ?? '-' }}</p>

                        <div class="flex items-center gap-3 mt-1">
                            <p class="text-xs text-slate-400">{{ $notif->created_at->diffForHumans() }}</p>

                            {{-- Tambahan link jika ada (sesuai notifikasi generator) --}}
                            @if(isset($data['action_url']))
                                <a href="{{ $data['action_url'] }}" class="text-xs text-blue-600 hover:underline">Lihat Detail</a>
                            @endif
                        </div>
                    </div>

                    <div>
                        <button class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-gray-200 transition">
                            <i data-lucide="more-horizontal" class="h-4 w-4"></i>
                        </button>
                    </div>

                </div>

                @empty
                <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-lg">
                    <i data-lucide="bell-off" class="mx-auto h-10 w-10 text-slate-300 mb-3"></i>
                    <p class="text-slate-500">Tidak ada notifikasi baru.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
@endsection
