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
        Schema::create('jenis_layanans', function (Blueprint $table) {
            $table->id(); // Pengganti id_layanan
            $table->string('nama_layanan');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_dasar', 10, 2)->default(0); // '10, 2' artinya total 10 digit, 2 di belakang koma (baik untuk uang)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_layanans');
    }
};
