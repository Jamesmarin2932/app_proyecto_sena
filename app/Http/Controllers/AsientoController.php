<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Cuenta;
use App\Models\CuentaEmpresa;
use App\Models\DatoCliente;
use App\Models\ConsecutivoAsiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AsientoController extends Controller
{
    /**
     * Guardar nuevo asiento contable
     */
  public function save(Request $request)
{
    $empresaId = $request->header('empresa_id');

    if (!$empresaId) {
        return response()->json(['error' => 'No se ha definido empresa activa'], 422);
    }

    $validatedData = $request->validate([
        'asientos' => 'required|array',
        'asientos.*.tipo'       => 'required|string|in:CC,RC,NC,CE',
        'asientos.*.fecha'      => 'required|date',
        'asientos.*.factura'    => 'nullable|string',
        'asientos.*.tercero_id' => 'nullable|integer|exists:dato_clientes,id',
        'asientos.*.cuenta'     => 'required|string',
        'asientos.*.concepto'   => 'required|string',
        'asientos.*.debito'     => 'required|numeric|min:0',
        'asientos.*.credito'    => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $tipo = $validatedData['asientos'][0]['tipo'];

        // Bloquear fila de consecutivo para evitar duplicados
        $consecutivoModel = ConsecutivoAsiento::where('empresa_id', $empresaId)
            ->where('tipo_asiento', $tipo)
            ->lockForUpdate()
            ->first();

        if (!$consecutivoModel) {
            $consecutivoModel = ConsecutivoAsiento::create([
                'empresa_id' => $empresaId,
                'tipo_asiento' => $tipo,
                'ultimo_consecutivo' => 0,
            ]);
        }

        // Incrementar consecutivo
        $consecutivoModel->ultimo_consecutivo++;
        $consecutivoModel->save();

        $numeroConsecutivo = $consecutivoModel->ultimo_consecutivo;
        $asientosGuardados = [];

        foreach ($validatedData['asientos'] as $datos) {
            $datos['consecutivo'] = $numeroConsecutivo;
            $datos['empresa_id']  = $empresaId;
            $datos['saldo']       = $datos['debito'] - $datos['credito'];

            // Crear cuentas
            $cuentaGlobal = Cuenta::firstOrCreate(
                ['codigo' => $datos['cuenta']],
                ['nombre' => 'Cuenta sin nombre']
            );

            CuentaEmpresa::firstOrCreate(
                [
                    'empresa_id' => $empresaId,
                    'codigo'     => $datos['cuenta'],
                ],
                ['nombre' => $cuentaGlobal->nombre]
            );

            $asientosGuardados[] = Asiento::create($datos);
        }

        DB::commit();

        return response()->json([
            'message'     => 'Asientos guardados correctamente',
            'consecutivo' => $numeroConsecutivo,
            'asientos'    => $asientosGuardados
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error'   => 'Error al guardar asientos',
            'detalle' => $e->getMessage(),
        ], 500);
    }
}


    /**
     * Listar todos los asientos de la empresa
     */
    public function index(Request $request)
    {
        $empresaId = $request->header('empresa_id');

        $asientos = Asiento::with('tercero')
            ->where('empresa_id', $empresaId)
            ->orderBy('consecutivo', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($asientos);
    }

    /**
     * Obtener último consecutivo por tipo
     */
public function ultimoConsecutivo($tipo)
{
    $empresaId = request()->header('empresa_id');

    if (!$empresaId) {
        return response()->json(['error' => 'No se ha definido empresa activa'], 422);
    }

    // Buscar o crear consecutivo
    $consecutivoModel = ConsecutivoAsiento::firstOrCreate(
        [
            'empresa_id' => $empresaId,
            'tipo_asiento' => $tipo
        ],
        [
            'ultimo_consecutivo' => 0
        ]
    );

    return response()->json([
        'consecutivo' => $consecutivoModel->ultimo_consecutivo
    ]);
}



    /**
     * Obtener asientos por ID
     */
    public function show($id)
    {
        try {
            $asiento = Asiento::with('tercero')->findOrFail($id);
            return response()->json($asiento);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }
    }

    /**
     * Obtener todos los asientos de un consecutivo
     */
    public function getByConsecutivoTipo($tipo, $consecutivo, Request $request)
{
    try {
        $empresaId = $request->header('empresa_id');

        if (!$empresaId) {
            return response()->json(['error' => 'No se ha definido empresa activa'], 422);
        }

        $asientos = Asiento::with('tercero')
            ->where('empresa_id', $empresaId)
            ->where('tipo', $tipo)
            ->where('consecutivo', $consecutivo)
            ->orderBy('id', 'asc')
            ->get();

        if ($asientos->isEmpty()) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }

        return response()->json($asientos);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    /**
     * Actualizar asiento individual
     */
    public function update(Request $request, $id)
    {
        try {
            $empresaId = $request->header('empresa_id');

            $asiento = Asiento::where('id', $id)
                ->where('empresa_id', $empresaId)
                ->firstOrFail();

            $validatedData = $request->validate([
                'tipo'       => 'required|string',
                'fecha'      => 'required|date',
                'factura'    => 'nullable|string',
                'tercero_id' => 'nullable|integer|exists:dato_clientes,id',
                'cuenta'     => 'required|string',
                'concepto'   => 'required|string',
                'debito'     => 'required|numeric|min:0',
                'credito'    => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            if ($asiento->cuenta !== $validatedData['cuenta']) {
                $cuentaGlobal = Cuenta::firstOrCreate(
                    ['codigo' => $validatedData['cuenta']],
                    ['nombre' => 'Cuenta sin nombre']
                );

                CuentaEmpresa::firstOrCreate(
                    [
                        'empresa_id' => $empresaId,
                        'codigo'     => $validatedData['cuenta'],
                    ],
                    [
                        'nombre' => $cuentaGlobal->nombre,
                    ]
                );
            }

            $validatedData['saldo'] = $validatedData['debito'] - $validatedData['credito'];
            $asiento->update($validatedData);

            DB::commit();

            return response()->json([
                'message' => 'Asiento actualizado correctamente',
                'asiento' => $asiento->load('tercero')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar asiento completo por consecutivo
     */
   public function updateByConsecutivo(Request $request, $consecutivo)
{
    $empresaId = $request->header('empresa_id');

    $validator = Validator::make($request->all(), [
        'asientos' => 'required|array',
        'asientos.*.tipo'       => 'required|string|in:CC,RC,NC,CE',
        'asientos.*.fecha'      => 'required|date',
        'asientos.*.factura'    => 'nullable|string',
        'asientos.*.tercero_id' => 'nullable|integer|exists:dato_clientes,id',
        'asientos.*.cuenta'     => 'required|string',
        'asientos.*.concepto'   => 'required|string',
        'asientos.*.debito'     => 'required|numeric|min:0',
        'asientos.*.credito'    => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json(['error' => $validator->errors()], 422);
    }

    DB::beginTransaction();

    try {
        // Obtener asientos existentes
        $existing = Asiento::where('consecutivo', $consecutivo)
            ->where('empresa_id', $empresaId)
            ->get();

        if ($existing->isEmpty()) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }

        // Borrar asientos antiguos del mismo consecutivo
        Asiento::where('consecutivo', $consecutivo)
            ->where('empresa_id', $empresaId)
            ->delete();

        $nuevos = [];
        foreach ($request->asientos as $data) {
            $cuentaGlobal = Cuenta::firstOrCreate(
                ['codigo' => $data['cuenta']],
                ['nombre' => 'Cuenta sin nombre']
            );

            CuentaEmpresa::firstOrCreate(
                [
                    'empresa_id' => $empresaId,
                    'codigo'     => $data['cuenta'],
                ],
                [
                    'nombre' => $cuentaGlobal->nombre,
                ]
            );

            $data['empresa_id']  = $empresaId;
            $data['consecutivo'] = $consecutivo; // Mantener el mismo consecutivo
            $data['saldo']       = $data['debito'] - $data['credito'];

            $nuevos[] = Asiento::create($data);
        }

        DB::commit();

        return response()->json([
            'message'  => 'Asiento actualizado correctamente',
            'asientos' => $nuevos
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error'   => 'Error al actualizar asiento',
            'detalle' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Eliminar asiento individual
     */
    public function destroy($id, Request $request)
    {
        try {
            $empresaId = $request->header('empresa_id');

            $asiento = Asiento::where('id', $id)
                ->where('empresa_id', $empresaId)
                ->firstOrFail();

            $asiento->delete();

            return response()->json(['message' => 'Asiento eliminado correctamente']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Eliminar todos los asientos de un consecutivo
     */
    public function deleteByConsecutivo($consecutivo, Request $request)
    {
        try {
            $empresaId = $request->header('empresa_id');

            $deleted = Asiento::where('consecutivo', $consecutivo)
                ->where('empresa_id', $empresaId)
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Asiento eliminado correctamente']);
            }

            return response()->json(['error' => 'Asiento no encontrado'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Establecer empresa actual del usuario
     */
    public function establecerEmpresaActual(Request $request)
    {
        try {
            $user = $request->user();
            $user->empresa_actual = $request->empresa_id;
            $user->save();

            return response()->json(['message' => 'Empresa actual establecida']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Obtener empresa actual del usuario
     */
    public function getEmpresaActual(Request $request)
    {
        try {
            $user = $request->user();
            return response()->json(['empresa_id' => $user->empresa_actual]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
