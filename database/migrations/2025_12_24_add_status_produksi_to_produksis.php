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
        Schema::table('produksis', function (Blueprint $table) {
            if (!Schema::hasColumn('produksis', 'status_produksi')) {
                $table->enum('status_produksi', ['pending', 'berjalan', 'selesai', 'tertunda'])->default('pending')->after('tanggal_selesai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produksis', function (Blueprint $table) {
            if (Schema::hasColumn('produksis', 'status_produksi')) {
                $table->dropColumn('status_produksi');
            }
        });
    }
};
