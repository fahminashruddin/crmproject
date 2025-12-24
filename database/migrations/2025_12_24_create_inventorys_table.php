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
        Schema::create('inventorys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produksi_id')->nullable()->constrained('produksis')->onDelete('set null');
            $table->string('nama_produk');
            $table->integer('jumlah')->default(0);
            $table->string('satuan')->default('pcs'); // pcs, ream, roll, dll
            $table->string('lokasi')->nullable(); // Lokasi penyimpanan
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventorys');
    }
};
