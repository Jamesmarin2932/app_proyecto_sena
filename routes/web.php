<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuenta;

Route::get('/importar-cuentas', function () {
    $path = storage_path('app/puc.csv');

    if (!file_exists($path)) {
        return 'Archivo no encontrado.';
    }

    $handle = fopen($path, 'r');
    fgetcsv($handle); // Saltar la cabecera

    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        Cuenta::create([
            'codigo' => $data[0],
            'nombre' => $data[1],
        ]);
    }

    fclose($handle);

    return '✅ Cuentas importadas con éxito.';
});
