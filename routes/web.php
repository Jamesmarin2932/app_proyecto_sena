<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

Route::get('/crear-usuario-admin', function () {
    if (User::where('usuario', 'admin')->exists()) {
        return '⚠️ El usuario admin ya existe.';
    }

    // Verifica si el rol "admin" existe. Si no, lo crea
    if (!Role::where('name', 'admin')->exists()) {
        Role::create(['name' => 'admin']);
    }

    $user = User::create([
        'nombre_usuario' => 'Administrador',
        'identificacion' => '123456789',
        'usuario' => 'admin',
        'password' => Hash::make('admin1234'),
    ]);

    $user->assignRole('admin');

    return '✅ Usuario admin creado con éxito. Puedes iniciar sesión con: usuario=admin, contraseña=admin1234';
});

Route::get('/ejecutar-migraciones', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        return '✅ Migraciones ejecutadas correctamente.';
    } catch (\Exception $e) {
        return '❌ Error al ejecutar migraciones: ' . $e->getMessage();
    }


    
});

Route::get('/probar-db', function () {
    try {
        DB::connection()->getPdo();
        return '✅ Laravel en producción se conectó a Supabase exitosamente.';
    } catch (\Exception $e) {
        return '❌ Error de conexión: ' . $e->getMessage();
    }
});
