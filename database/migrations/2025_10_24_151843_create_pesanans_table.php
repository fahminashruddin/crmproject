<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id(); // Pengganti id_pesanan
            $table->date('tanggal_pesanan');
            $table->text('catatan')->nullable();

            // --- FOREIGN KEYS ---
            $table->foreignId('pelanggan_id')->constrained('pelanggans');
            $table->foreignId('pengguna_id')->constrained('penggunas'); // Staf yg menangani
            $table->foreignId('status_pesanan_id')->constrained('status_pesanans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanans');
    }
};
