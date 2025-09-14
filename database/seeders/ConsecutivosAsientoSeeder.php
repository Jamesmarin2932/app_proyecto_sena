<?php
// database/seeders/ConsecutivosAsientoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\ConsecutivoAsiento;

class ConsecutivosAsientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las empresas existentes en tu sistema
        $empresas = Empresa::all();
        
        if ($empresas->count() === 0) {
            $this->command->info('⚠️  No se encontraron empresas en el sistema.');
            $this->command->info('💡 Ejecuta primero los seeders de empresas o crea empresas manualmente.');
            return;
        }

        $this->command->info("📋 Encontradas {$empresas->count()} empresas en el sistema");
        
        $tipos = ['CC', 'RC', 'NC', 'CE'];
        $registrosCreados = 0;
        $registrosExistentes = 0;

        foreach ($empresas as $empresa) {
            $this->command->info("🏢 Procesando empresa: {$empresa->nombre} (ID: {$empresa->id})");
            
            foreach ($tipos as $tipo) {
                // Verificar si ya existe el registro para evitar duplicados
                $existe = ConsecutivoAsiento::where('empresa_id', $empresa->id)
                    ->where('tipo_asiento', $tipo)
                    ->exists();

                if (!$existe) {
                    ConsecutivoAsiento::create([
                        'empresa_id' => $empresa->id,
                        'tipo_asiento' => $tipo,
                        'ultimo_consecutivo' => $this->obtenerUltimoConsecutivoExistente($empresa->id, $tipo),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $registrosCreados++;
                    $this->command->info("   ✅ Creado consecutivo para {$tipo}");
                } else {
                    $registrosExistentes++;
                    $this->command->info("   ⏭️  Consecutivo para {$tipo} ya existe");
                }
            }
        }

        $this->command->info("🎉 Proceso completado!");
        $this->command->info("📊 Registros creados: {$registrosCreados}");
        $this->command->info("📊 Registros existentes: {$registrosExistentes}");
        $this->command->info("💡 Total de registros: " . ($registrosCreados + $registrosExistentes));
    }

    /**
     * Obtiene el último consecutivo existente de asientos para mantener la numeración
     */
    private function obtenerUltimoConsecutivoExistente($empresaId, $tipo): int
    {
        // Si ya hay asientos de este tipo, obtener el máximo consecutivo
        $ultimoConsecutivo = DB::table('asientos')
            ->where('empresa_id', $empresaId)
            ->where('tipo', $tipo)
            ->max('consecutivo');
        
        return $ultimoConsecutivo ?: 0;
    }
}