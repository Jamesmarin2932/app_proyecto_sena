<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class GlobalCuentasSeeder extends Seeder
{
    /**
     * Ejecuta el seeder de cuentas globales.
     */
    public function run(): void
    {
        $csvPath = database_path('seeders/csv/cuentas_globales.csv'); // Ruta de tu CSV

        if (!file_exists($csvPath)) {
            $this->command->error("Archivo CSV no encontrado en: $csvPath");
            return;
        }

        $csv = Reader::createFromPath($csvPath, 'r');
        $csv->setHeaderOffset(0); // Primera fila como cabecera

        $records = iterator_to_array($csv->getRecords());

        DB::table('cuentas')->upsert(
            array_map(fn($r) => [
                'codigo' => trim($r['Codigo']),
                'nombre' => trim($r['Nombre']),
                'created_at' => now(),
                'updated_at' => now()
            ], $records),
            ['codigo'], // clave única para no duplicar
            ['nombre', 'updated_at'] // columnas a actualizar si existe
        );

        $this->command->info('✅ Cuentas globales cargadas: ' . count($records));
    }
}
