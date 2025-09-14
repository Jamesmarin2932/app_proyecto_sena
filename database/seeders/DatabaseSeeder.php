<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ... otros seeders que ya tengas
        
        // Agregar el nuevo seeder de consecutivos
        $this->call(ConsecutivosAsientoSeeder::class);
    }
}