<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

Route::get('/crear-usuario', function () {
    $user = User::create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => Hash::make('password123'), // Cambia esto si quieres otra clave
    ]);

    return '✅ Usuario creado: ' . $user->email;
});
