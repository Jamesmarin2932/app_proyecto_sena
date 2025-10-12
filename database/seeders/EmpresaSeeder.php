<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('empresas')->insert([
            [
                'nombre_razon_social' => 'Mi Empresa Principal S.A.S.',
                'nombre_comercial' => 'MiEmpresa',
                'nit' => '900123456-7',
                'direccion' => 'Calle Falsa 123',
                'ciudad' => 'Bogotá',
                'departamento' => 'Cundinamarca',
                'pais' => 'Colombia',
                'actividad_economica' => 'Servicios de software',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
