<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Pembayaran;

class PembayaranTerverifikasiNotification extends Notification
{
    use Queueable;

    public $pembayaran;

    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'payment',
            'title' => 'Pembayaran Terverifikasi',
            'message' => 'Pembayaran sejumlah Rp ' . number_format($this->pembayaran->nominal, 0, ',', '.') . ' telah diverifikasi.',
            'action_url' => route('admin.payments'),
            'created_at' => now(),
        ];
    }
}
