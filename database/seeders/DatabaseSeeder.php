<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(StatusPesananSeeder::class);
        $this->call(PelangganSeeder::class);
        $this->call(PenggunaSeeder::class);
        $this->call(JenisLayananSeeder::class);
        $this->call(PesananSeeder::class);
        $this->call(DetailPesananSeeder::class);
    }
}
