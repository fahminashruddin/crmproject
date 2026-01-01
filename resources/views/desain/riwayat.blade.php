@extends('desain.layout')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">

        <h1 class="text-3xl font-bold text-gray-900 mb-1">Riwayat Desain</h1>
        <p class="text-gray-600 mb-6">Lihat semua desain yang pernah dibuat</p>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold mb-4">Desain Terbaru</h2>

            @forelse ($riwayat as $item)
                <div class="flex justify-between items-center border rounded-lg p-4 mb-3">
                    <div>
                        <p class="font-semibold text-gray-900">
                            {{ basename($item->file_desain_path) }}
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ $item->nama_pelanggan }} • 
                            {{ \Carbon\Carbon::parse($item->updated_at)->format('Y-m-d') }}
                        </p>
                    </div>

                    <span class="px-3 py-1 text-sm rounded-full bg-gray-900 text-white">
                        {{ $item->nama_status }}
                    </span>
                </div>
            @empty
                <p class="text-gray-500 text-center">
                    Belum ada riwayat desain.
                </p>
            @endforelse

        </div>
    </div>
</div>
@endsection
