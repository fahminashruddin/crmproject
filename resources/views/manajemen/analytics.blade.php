@extends('manajemen.layout')
@section('title', 'Analytics')

@section('content')

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>*{font-family:'Inter',sans-serif}</style>

<div class="space-y-8">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2">
            <i data-lucide="line-chart" class="w-6 text-gray-700"></i>
            <h2 class="text-2xl font-bold text-gray-900">Analytics</h2>
        </div>
        <p class="text-sm text-gray-500">Analisis Mendalam Performa Bisnis</p>
    </div>

    <!-- FILTER -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Dari</label>
                <input type="date" name="start_date" value="{{ $start }}" 
                       class="w-full px-4 py-2 border rounded-2xl focus:ring-2">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-600 mb-1">Sampai</label>
                <input type="date" name="end_date" value="{{ $end }}" 
                       class="w-full px-4 py-2 border rounded-2xl focus:ring-2">
            </div>

            <div class="flex items-end">
                <button class="w-full bg-indigo-600 text-white py-2 rounded-2xl hover:scale-105 hover:bg-indigo-700 transition shadow-md">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- CHART SECTION -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- LINE CHART -->
        <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-xl transition">
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="trending-up" class="w-5 text-gray-700"></i>
                <h3 class="text-lg font-bold text-gray-900">Tren Pesanan</h3>
            </div>
            <canvas id="lineChart" height="120"></canvas>
        </div>

        <!-- BAR CHART -->
        <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-xl transition">
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="bar-chart-3" class="w-5 text-gray-700"></i>
                <h3 class="text-lg font-bold text-gray-900">Pendapatan per Layanan</h3>
            </div>
            <canvas id="barChart" height="120"></canvas>
        </div>

    </div>

    {{-- <!-- DONUT CHART (opsional analytics tambahan) -->
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-100 hover:shadow-xl transition">
        <div class="flex items-center gap-2 mb-4">
            <i data-lucide="pie-chart" class="w-5 text-gray-700"></i>
            <h3 class="text-lg font-bold text-gray-900">Distribusi Pendapatan</h3>
        </div>
        <div class="w-full md:w-1/2 mx-auto">
            <canvas id="donutChart" height="120"></canvas>
        </div>
    </div> --}}

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // LINE CHART
    new Chart(document.getElementById('lineChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($trenPesanan->pluck('tanggal')) !!},
            datasets: [{
                label: 'Jumlah Pesanan',
                data: {!! json_encode($trenPesanan->pluck('total')) !!},
                borderWidth: 2,
                tension: 0.35,
                fill: false
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { font: { size: 12 } } }
            }
        }
    });

    // BAR CHART
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiPendapatan->pluck('nama_layanan')) !!},
            datasets: [{
                label: 'Total Pendapatan',
                data: {!! json_encode($distribusiPendapatan->pluck('total')) !!},
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: v => 'Rp ' + v.toLocaleString()
                    }
                }
            },
            plugins: {
                legend: { labels: { font: { size: 12 } } }
            }
        }
    });

    // DONUT CHART
    new Chart(document.getElementById('donutChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($distribusiPendapatan->pluck('nama_layanan')) !!},
            datasets: [{
                data: {!! json_encode($distribusiPendapatan->pluck('total')) !!}
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                }
            }
        }
    });

    lucide.createIcons();
</script>

@endsection
