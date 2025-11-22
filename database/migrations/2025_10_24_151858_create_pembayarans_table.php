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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id(); // Pengganti id_pembayaran
            $table->decimal('nominal', 10, 2);
            $table->string('bukti_bayar_path')->nullable();
            $table->date('tanggal_bayar');
            $table->string('status', 50)->default('pending');

            // --- FOREIGN KEYS ---
            $table->foreignId('pesanan_id')->constrained('pesanans');
            $table->foreignId('metode_pembayaran_id')->constrained('metode_pembayarans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
