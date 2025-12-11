@extends('desain.layout') 

@section('content')
<div class="space-y-6">

    {{-- Header sesuai gambar --}}
    <div>
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900">Template Desain</h1>
        <p class="text-slate-500 mt-1">Kelola template desain untuk mempercepat proses.</p>
    </div>
    
    {{-- Konten Utama - Card Container --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="p-5 border-b border-slate-200">
            <h2 class="text-lg font-medium text-slate-700">Template Tersedia</h2>
        </div>
        <div class="p-6">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                @forelse ($templates as $template)
                <div class="col-span-1">
                    {{-- Card untuk setiap Template (Jenis Layanan) --}}
                    <div class="bg-white border border-slate-300 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition duration-200 h-full flex flex-col">
                        
                        <div class="p-6 text-center flex flex-col items-center justify-center flex-grow">
                            
                            {{-- Placeholder untuk Gambar Template (Lucide Icon) --}}
                            <div class="mb-4 bg-slate-100 p-3 rounded-full">
                                <i data-lucide="archive" class="h-6 w-6 text-slate-400"></i>
                            </div>
                            
                            {{-- Nama Template/Layanan --}}
                            <h3 class="text-lg font-semibold text-slate-800 mt-2">{{ $template->nama_layanan }}</h3>
                            
                            {{-- Deskripsi Sederhana --}}
                            <p class="text-sm text-slate-500 mt-1">
                                @if(strlen($template->deskripsi) > 50)
                                    {{ substr($template->deskripsi, 0, 50) . '...' }}
                                @else
                                    Template siap pakai
                                @endif
                            </p>
                        </div>
                        
                        {{-- Footer Aksi --}}
                        <div class="p-4 border-t bg-slate-50 text-center">
                            {{-- Tombol Aksi --}}
                            <a href="#" class="inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition duration-150">
                                Gunakan Template
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full">
                    <div class="p-6 text-center bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-yellow-700 font-medium">Belum ada jenis layanan yang terdaftar sebagai template.</p>
                    </div>
                </div>
                @endforelse

            </div>

        </div>
    </div>

</div>
@endsection