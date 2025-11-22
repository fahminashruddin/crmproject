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
        Schema::create('desains', function (Blueprint $table) {
            $table->id(); // Pengganti id_desain
            $table->string('file_desain_path')->nullable();
            $table->text('catatan_revisi')->nullable();

            // --- FOREIGN KEYS ---
            $table->foreignId('status_desain_id')->constrained('status_desains');
            $table->foreignId('pesanan_id')->constrained('pesanans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('desains');
    }
};
