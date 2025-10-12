<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario admin
        $usuarioId = DB::table('users')->insertGetId([
            'nombre_usuario' => 'Administrador General',
            'identificacion' => '100000001',
            'usuario' => 'Administrador',
            'password' => Hash::make('camisanegra123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Obtener la primera empresa
        $empresaId = DB::table('empresas')->first()->id;

        // Asociar usuario con empresa en la tabla pivote
        DB::table('empresa_usuario')->insert([
            'user_id' => $usuarioId,
            'empresa_id' => $empresaId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
