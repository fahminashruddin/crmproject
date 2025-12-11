<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Sistem</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 12px;
            padding: 20px;
        }

        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .period {
            font-size: 12px;
            color: #666;
        }

        .card {
            border: 1px solid #ddd;
            padding: 12px;
            border-radius: 6px;
            width: 23%;
            display: inline-block;
            margin-right: 1%;
            margin-bottom: 15px;
            vertical-align: top;
            background: #fafafa;
        }

        .card h3 {
            font-size: 14px;
            color: #444;
            margin-bottom: 5px;
        }

        .card .value {
            font-size: 18px;
            font-weight: bold;
            color: #111;
        }

        .section-title {
            margin-top: 25px;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 10px;
        }

        table thead {
            background: #333;
            color: #fff;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        .small {
            font-size: 11px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Sistem</h1>
        <div class="period">
            Periode:
            {{ $start ? $start : 'Semua' }} -
            {{ $end ? $end : 'Semua' }}
        </div>
    </div>

    <!-- RINGKASAN -->
    <div class="card">
        <h3>Total Pesanan</h3>
        <div class="value">{{ $totalPesanan }}</div>
    </div>

    <div class="card">
        <h3>Pesanan Selesai</h3>
        <div class="value">{{ $selesai }}</div>
    </div>

    <div class="card">
        <h3>Total Pendapatan</h3>
        <div class="value">Rp {{ number_format($totalPendapatan,0,',','.') }}</div>
    </div>

    <div class="card">
        <h3>Pending</h3>
        <div class="value">{{ $pending }}</div>
    </div>


    <!-- PER LAYANAN -->
    <div class="section-title">Performa per Layanan</div>

    <table>
        <thead>
            <tr>
                <th>Layanan</th>
                <th>Jumlah Pesanan</th>
                <th>Total Nominal</th>
                <th>Rata-rata</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ringkasan['perLayanan'] as $l)
            <tr>
                <td>{{ $l->nama_layanan }}</td>
                <td>{{ $l->jumlah_pesanan }}</td>
                <td>Rp {{ number_format($l->total_nominal,0,',','.') }}</td>
                <td>Rp {{ number_format($l->rata_rata,0,',','.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <!-- DETAIL PESANAN -->
    <div class="section-title">Detail Pesanan</div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Pelanggan</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($detailPesanan as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td>{{ $p->pelanggan }}</td>
                <td>{{ $p->nama_layanan }}</td>
                <td>{{ $p->tanggal_pesanan }}</td>
                <td>{{ $p->nama_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
