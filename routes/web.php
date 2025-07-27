<?php

use Illuminate\Support\Facades\Route;
use App\Models\Cuenta;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;


Route::get('/importar-cuentas', function () {
    $file = public_path('archivos/puc.csv');

    if (!file_exists($file)) {
        return response('❌ Archivo no encontrado.', 404);
    }

    $handle = fopen($file, 'r');
    fgetcsv($handle); // Omitir encabezado

    $importadas = 0;

    while (($data = fgetcsv($handle, 1000, ',')) !== false) {
        // Verificar que no esté vacía la fila
        if (isset($data[0]) && isset($data[1])) {
            Cuenta::create([
                'codigo' => $data[0],
                'nombre' => $data[1],
            ]);
            $importadas++;
        }
    }

    fclose($handle);

    return "✅ Se importaron {$importadas} cuentas con éxito.";
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


Route::get('/', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API Laravel funcionando en Render 🎉'
    ]);
});