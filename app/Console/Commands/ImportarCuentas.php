<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cuenta;

class ImportarCuentas extends Command
{
    protected $signature = 'importar:cuentas';
    protected $description = 'Importar cuentas contables desde puc.csv';

    public function handle()
    {
        $path = storage_path('app/puc.csv');

        if (!file_exists($path)) {
            $this->error('❌ Archivo puc.csv no encontrado en storage/app');
            return;
        }

        $handle = fopen($path, 'r');
        fgetcsv($handle); // Saltar cabecera

        $contador = 0;

       while (($data = fgetcsv($handle, 1000, ',')) !== false) {
    // Verifica que haya al menos dos columnas y que no esté vacía la fila
    if (count($data) < 2 || empty($data[0]) || empty($data[1])) {
        continue; // Saltar filas vacías o incompletas
    }

    Cuenta::updateOrCreate(
        ['codigo' => $data[0]],
        ['nombre' => $data[1]]
    );
    $contador++;
}


        fclose($handle);

        $this->info("✅ $contador cuentas importadas con éxito.");
    }
}
