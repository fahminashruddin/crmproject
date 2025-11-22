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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id(); // Pengganti id_log
            $table->text('aktivitas');
            $table->text('detail')->nullable();

            // --- FOREIGN KEY ---
            $table->foreignId('pengguna_id')->constrained('penggunas');

            $table->timestamps(); // 'waktu' sudah dicatat oleh timestamps()
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
