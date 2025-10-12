<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta todos los seeders de la aplicación.
     */
    public function run(): void
    {
        // 🌱 Orden recomendado de ejecución
        // 1. Tablas base (catálogos, configuraciones, etc.)
        // 2. Tablas de relaciones principales (empresas, usuarios)
        // 3. Seeders complementarios (consecutivos, parámetros, etc.)

       $this->call([
    // 🌱 Seeders base
    GlobalCuentasSeeder::class,      // <- NUEVO, primero para que las cuentas existan

    // 🌱 Tablas principales
    EmpresaSeeder::class,
    UserSeeder::class,

    // 🌱 Seeders complementarios
    ConsecutivosAsientoSeeder::class,
]);

    }
}
