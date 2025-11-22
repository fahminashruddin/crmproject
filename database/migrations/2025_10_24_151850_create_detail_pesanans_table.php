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
        Schema::create('detail_pesanans', function (Blueprint $table) {
            $table->id(); // Pengganti id_detail_pesanan
            $table->text('spesifikasi');
            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 10, 2); // 10 digit total, 2 di belakang koma

            // --- FOREIGN KEYS ---
            $table->foreignId('pesanan_id')->constrained('pesanans')->onDelete('cascade');
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pesanans');
    }
};
