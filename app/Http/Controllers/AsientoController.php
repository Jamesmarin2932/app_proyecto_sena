<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Cuenta;
use App\Models\DatoCliente; // Modelo para terceros
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    public function save(Request $request)
    {
        $validatedData = $request->validate([
            'asientos' => 'required|array',
            'asientos.*.tipo' => 'required|string',
            'asientos.*.fecha' => 'required|date',
            'asientos.*.factura' => 'nullable|string',
            'asientos.*.tercero_id' => 'nullable|integer|exists:dato_clientes,id',
            'asientos.*.cuenta' => 'required|string',
            'asientos.*.concepto' => 'required|string',
            'asientos.*.debito' => 'required|numeric|min:0',
            'asientos.*.credito' => 'required|numeric|min:0',
            'asientos.*.consecutivo' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $ultimoConsecutivo = Asiento::max('consecutivo') ?? 0;

            foreach ($validatedData['asientos'] as &$datos) {
                if (empty($datos['consecutivo'])) {
                    $ultimoConsecutivo++;
                    $datos['consecutivo'] = $ultimoConsecutivo;
                }

                $datos['saldo'] = $datos['debito'] - $datos['credito'];

                $asiento = new Asiento([
                    'tipo' => $datos['tipo'],
                    'fecha' => $datos['fecha'],
                    'factura' => $datos['factura'],
                    'tercero_id' => $datos['tercero_id'] ?? null,

                    'cuenta' => $datos['cuenta'],
                    'concepto' => $datos['concepto'],
                    'debito' => $datos['debito'],
                    'credito' => $datos['credito'],
                    'saldo' => $datos['saldo'],
                    'consecutivo' => $datos['consecutivo'],
                ]);
                $asiento->save();

                $cuenta = Cuenta::firstOrCreate(
                    ['codigo' => $datos['cuenta']],
                    ['nombre' => 'Cuenta sin nombre']
                );

                // Opcional actualizar saldo en cuenta
                // $cuenta->saldo += $datos['saldo'];
                // $cuenta->save();
            }

            DB::commit();

            return response()->json(['message' => 'Asientos guardados correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar asientos',
                'detalle' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        $asientos = Asiento::with('tercero')->orderBy('consecutivo', 'asc')->get();

        foreach ($asientos as $asiento) {
            $cuenta = Cuenta::where('codigo', $asiento->cuenta)->first();
            $asiento->saldo_actual = $cuenta ? $cuenta->saldo : 0;
            $asiento->nombre_tercero = $asiento->tercero ? $asiento->tercero->nombre : null;
        }

        return response()->json($asientos);
    }

    public function ultimoConsecutivo()
    {
        try {
            $ultimoConsecutivo = Asiento::max('consecutivo') ?? 0;
            return response()->json(['consecutivo' => $ultimoConsecutivo]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el último consecutivo',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
