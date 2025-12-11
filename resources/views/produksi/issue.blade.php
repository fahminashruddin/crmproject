@extends('produksi.layout')

@section('title', 'Daftar Kendala')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center border-b border-slate-200 pb-5">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-slate-900">Laporan Kendala</h2>
            <p class="text-slate-500 mt-1">Daftar masalah yang dilaporkan selama proses produksi.</p>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b">
                <tr>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">ID Pesanan</th>
                    <th class="px-6 py-3">Pelanggan</th>
                    <th class="px-6 py-3">Deskripsi Masalah</th>
                    <th class="px-6 py-3">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($issues as $issue)
                <tr class="hover:bg-slate-50">
                    <td class="px-6 py-4 text-slate-500">
                        {{ \Carbon\Carbon::parse($issue->created_at)->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 font-mono font-medium text-slate-900">
                        ORD-{{ str_pad($issue->order_id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 text-slate-700">
                        {{ $issue->nama_pelanggan }}
                    </td>
                    <td class="px-6 py-4 text-slate-600 italic">
                        "{{ $issue->deskripsi }}"
                    </td>
                    <td class="px-6 py-4">
                        @if($issue->status == 'Pending')
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                {{ $issue->status }}
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                {{ $issue->status }}
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <div class="flex flex-col items-center justify-center">
                            <i data-lucide="check-circle" class="h-8 w-8 text-green-500 mb-2"></i>
                            <p>Tidak ada kendala yang dilaporkan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-slate-100">
            {{ $issues->links() }}
        </div>
    </div>
</div>
@endsection