@extends('manajemen.layout')

@section('title', 'Analytics')

@section('content')

<div class="space-y-8">

    <h2 class="text-2xl font-bold mb-4">
        Analytics – Analisis Mendalam Performa Bisnis
    </h2>

    <!-- Filter -->
    <div class="bg-white p-6 rounded-xl shadow-sm border">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="font-semibold">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $start }}" class="w-full mt-1 border p-2 rounded-lg">
            </div>

            <div>
                <label class="font-semibold">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $end }}" class="w-full mt-1 border p-2 rounded-lg">
            </div>

            <div class="flex items-end">
                <button class="w-full bg-purple-600 text-white py-2 rounded-lg">Filter</button>
            </div>
        </form>
    </div>

    <!-- CHARTS 2 KOLOM -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Tren Pesanan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="text-lg font-semibold mb-4">Tren Pesanan</h3>
            <canvas id="lineChart" height="130"></canvas>
        </div>

        <!-- Distribusi Pendapatan -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <h3 class="text-lg font-semibold mb-4">Distribusi Pendapatan</h3>
            <canvas id="barChart" height="130"></canvas>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // LINE CHART
    const lineCtx = document.getElementById('lineChart');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenPesanan->pluck('tanggal')) !!},
            datasets: [{
                label: 'Jumlah Pesanan',
                data: {!! json_encode($trenPesanan->pluck('total')) !!},
                borderWidth: 2,
                borderColor: '#4F46E5',
                tension: 0.3,
                fill: false
            }]
        },
    });

    // BAR CHART
    const barCtx = document.getElementById('barChart');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiPendapatan->pluck('nama_layanan')) !!},
            datasets: [{
                label: 'Total Pendapatan',
                data: {!! json_encode($distribusiPendapatan->pluck('total')) !!},
                backgroundColor: '#10B981', // warna bar, bisa diganti
                borderColor: '#059669',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
