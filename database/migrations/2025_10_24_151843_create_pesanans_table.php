<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); // Pengganti id_pesanan
            $table->date('tanggal_pesanan');
            $table->text('catatan')->nullable();
            $table->foreignId('pelanggan_id')->constrained('pelanggans');
            $table->foreignId('pengguna_id')->constrained('penggunas');
            $table->foreignId('status_pesanan_id')->constrained('status_pesanans');
            $table->boolean('is_synced_bonobo')->default(0)->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
