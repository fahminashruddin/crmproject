<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanExport implements FromArray, WithHeadings, WithStyles
{
    protected $start;
    protected $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function headings(): array
    {
        return [
            'ID Pesanan',
            'Nama Pelanggan',
            'Layanan',
            'Tanggal Pesanan',
            'Status Pesanan',
            'Nominal Pembayaran',
        ];
    }

    public function array(): array
{
    $query = DB::table('pesanans')
        ->leftJoin('pelanggans', 'pesanans.pelanggan_id', '=', 'pelanggans.id')
        ->leftJoin('detail_pesanans', 'detail_pesanans.pesanan_id', '=', 'pesanans.id') // FIX
        ->leftJoin('jenis_layanans', 'detail_pesanans.jenis_layanan_id', '=', 'jenis_layanans.id') // FIX
        ->leftJoin('status_pesanans', 'pesanans.status_pesanan_id', '=', 'status_pesanans.id')
        ->leftJoin('pembayarans', 'pembayarans.pesanan_id', '=', 'pesanans.id')
        ->select(
            'pesanans.id',
            'pelanggans.nama as pelanggan',
            'jenis_layanans.nama_layanan',
            'pesanans.tanggal_pesanan',
            'status_pesanans.nama_status',
            DB::raw('COALESCE(pembayarans.nominal, 0) as nominal')
        );

    // filter tanggal
    if ($this->start && $this->end) {
        $query->whereBetween('pesanans.tanggal_pesanan', [$this->start, $this->end]);
    }

    return $query->get()->map(function ($item) {
        return [
            $item->id,
            $item->pelanggan,
            $item->nama_layanan,
            $item->tanggal_pesanan,
            $item->nama_status,
            $item->nominal,
        ];
    })->toArray();
}


    public function styles(Worksheet $sheet)
    {
        // Bold untuk header
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
