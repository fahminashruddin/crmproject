@extends('layouts.admin')

@section('title', 'Notifikasi')

@section('content')
    <div class="p-8">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">Notifikasi</h1>
            <p class="text-slate-500 mt-1">Kelola notifikasi sistem</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-100">
                <h2 class="text-lg font-bold text-slate-900">Notifikasi Terbaru</h2>
            </div>

            <div class="p-6 space-y-4">
                @forelse($notifications as $notif)

                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-slate-50 transition-colors {{ $notif->is_read ? 'opacity-75' : 'bg-white' }}">

                    <div class="mr-4 flex-shrink-0">
                        @if($notif->type == 'order')
                            <div class="p-2 bg-blue-50 rounded-full text-blue-600">
                                <i data-lucide="bell" class="h-5 w-5"></i>
                            </div>
                        @elseif($notif->type == 'payment')
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
                            {{ $notif->title }}
                            @if(!$notif->is_read)
                                <span class="ml-2 inline-block w-2 h-2 bg-red-500 rounded-full" title="Belum dibaca"></span>
                            @endif
                        </h3>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $notif->message }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}</p>
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

            {{-- <div class="p-6 border-t border-gray-100">
                {{ $notifications->links() }}
            </div> --}}
        </div>

    </div>
@endsection
