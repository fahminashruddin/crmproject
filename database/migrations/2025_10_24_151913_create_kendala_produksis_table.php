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
        Schema::create('kendala_produksis', function (Blueprint $table) {
            $table->id(); // Pengganti id_kendala
            $table->text('deskripsi_kendala');
            $table->dateTime('waktu_terjadi');

            // --- FOREIGN KEY ---
            $table->foreignId('produksi_id')->constrained('produksis');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendala_produksis');
    }
};
