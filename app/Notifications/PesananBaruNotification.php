<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Pesanan;

class PesananBaruNotification extends Notification
{
    use Queueable;

    public $pesanan;

    public function __construct(Pesanan $pesanan)
    {
        $this->pesanan = $pesanan;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan ke database
    }

    // Data yang akan disimpan di kolom 'data' database
    public function toArray($notifiable)
    {
        return [
            'type' => 'order', // Untuk ikon/warna di view
            'title' => 'Pesanan Baru Masuk',
            'message' => 'Pesanan ORD-' . str_pad($this->pesanan->id, 3, '0', STR_PAD_LEFT) . ' dari ' . ($this->pesanan->pelanggan->nama ?? 'Pelanggan'),
            'action_url' => route('admin.orders', ['search' => $this->pesanan->id]), // Link langsung ke pesanan
            'created_at' => now(),
        ];
    }
}
