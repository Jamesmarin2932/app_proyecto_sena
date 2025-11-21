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
use Barryvdh\DomPDF\Facade\Pdf;

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
            'asientos.*.tipo' => 'required|string|in:CC,RC,NC,CE',
            'asientos.*.fecha' => 'required|date',
            'asientos.*.factura' => 'nullable|string',
            'asientos.*.tercero_id' => 'nullable|integer|exists:dato_clientes,id',
            'asientos.*.cuenta' => 'required|string',
            'asientos.*.nombre_cuenta' => 'nullable|string',
            'asientos.*.concepto' => 'required|string',
            'asientos.*.debito' => 'required|numeric|min:0',
            'asientos.*.credito' => 'required|numeric|min:0',
            'asientos.*.consecutivo' => 'nullable|integer',
            'asientos.*.usuario_creador' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $tipo = $validatedData['asientos'][0]['tipo'];
            $consecutivoManual = $validatedData['asientos'][0]['consecutivo'] ?? null;

            // Obtener o crear consecutivo
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

            if ($consecutivoManual) {
                if ($consecutivoManual > $consecutivoModel->ultimo_consecutivo) {
                    $consecutivoModel->ultimo_consecutivo = $consecutivoManual;
                    $consecutivoModel->save();
                }
                $numeroConsecutivo = $consecutivoManual;
            } else {
                $consecutivoModel->ultimo_consecutivo++;
                $consecutivoModel->save();
                $numeroConsecutivo = $consecutivoModel->ultimo_consecutivo;
            }

            $asientosGuardados = [];

            foreach ($validatedData['asientos'] as $datos) {
                $datos['consecutivo'] = $numeroConsecutivo;
                $datos['empresa_id'] = $empresaId;
                $datos['saldo'] = $datos['debito'] - $datos['credito'];

                if (!isset($datos['usuario_creador']) || empty($datos['usuario_creador'])) {
                    $datos['usuario_creador'] = auth()->user()->nombre_usuario ?? 'Usuario Sistema';
                }

                // Obtener cuenta global sin crear automáticamente cuenta empresa
                $cuentaGlobal = Cuenta::where('codigo', $datos['cuenta'])->first();

                if (!$cuentaGlobal) {
                    // Si no existe la cuenta global, crearla con nombre proporcionado o fallback
                    $cuentaGlobal = Cuenta::create([
                        'codigo' => $datos['cuenta'],
                        'nombre' => $datos['nombre_cuenta'] ?? 'Cuenta sin nombre'
                    ]);
                }

                // Si no existe nombre en el asiento, tomar de la cuenta global
                $datos['nombre_cuenta'] = $datos['nombre_cuenta'] ?? $cuentaGlobal->nombre;

                $asientosGuardados[] = Asiento::create($datos);
            }

            DB::commit();

            return response()->json([
                'message' => 'Asientos guardados correctamente',
                'consecutivo' => $numeroConsecutivo,
                'asientos' => $asientosGuardados
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al guardar asientos',
                'detalle' => $e->getMessage()
            ], 500);
        }
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
     * Listar todos los asientos
     */
    public function index(Request $request)
    {
        $empresaId = $request->header('empresa_id');

        $asientos = Asiento::with(['tercero', 'cuentaInfo', 'cuentaEmpresa'])
            ->where('empresa_id', $empresaId)
            ->orderBy('consecutivo', 'desc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($asientos);
    }

    /**
     * Obtener asiento individual
     */
    public function show($id)
    {
        try {
            $asiento = Asiento::with(['tercero', 'cuentaInfo', 'cuentaEmpresa'])->findOrFail($id);
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

            $asientos = Asiento::with(['tercero', 'cuentaInfo', 'cuentaEmpresa'])
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
                'tipo' => 'required|string',
                'fecha' => 'required|date',
                'factura' => 'nullable|string',
                'tercero_id' => 'nullable|integer|exists:dato_clientes,id',
                'cuenta' => 'required|string',
                'nombre_cuenta' => 'nullable|string',
                'concepto' => 'required|string',
                'debito' => 'required|numeric|min:0',
                'credito' => 'required|numeric|min:0',
                'usuario_creador' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Obtener cuenta global
            $cuentaGlobal = Cuenta::where('codigo', $validatedData['cuenta'])->first();

            if (!$cuentaGlobal) {
                $cuentaGlobal = Cuenta::create([
                    'codigo' => $validatedData['cuenta'],
                    'nombre' => $validatedData['nombre_cuenta'] ?? 'Cuenta sin nombre'
                ]);
            }

            $validatedData['nombre_cuenta'] = $validatedData['nombre_cuenta'] ?? $cuentaGlobal->nombre;
            $validatedData['saldo'] = $validatedData['debito'] - $validatedData['credito'];

            $asiento->update($validatedData);

            DB::commit();

            return response()->json([
                'message' => 'Asiento actualizado correctamente',
                'asiento' => $asiento->load(['tercero', 'cuentaInfo', 'cuentaEmpresa'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualizar por consecutivo
     */
    public function updateByConsecutivo(Request $request, $consecutivo)
    {
        $empresaId = $request->header('empresa_id');

        $validator = Validator::make($request->all(), [
            'asientos' => 'required|array',
            'asientos.*.tipo' => 'required|string|in:CC,RC,NC,CE',
            'asientos.*.fecha' => 'required|date',
            'asientos.*.factura' => 'nullable|string',
            'asientos.*.tercero_id' => 'nullable|integer|exists:dato_clientes,id',
            'asientos.*.cuenta' => 'required|string',
            'asientos.*.nombre_cuenta' => 'nullable|string',
            'asientos.*.concepto' => 'required|string',
            'asientos.*.debito' => 'required|numeric|min:0',
            'asientos.*.credito' => 'required|numeric|min:0',
            'asientos.*.usuario_creador' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        DB::beginTransaction();

        try {
            $existing = Asiento::where('consecutivo', $consecutivo)
                ->where('empresa_id', $empresaId)
                ->get();

            if ($existing->isEmpty()) {
                return response()->json(['error' => 'Asiento no encontrado'], 404);
            }

            // Eliminar antiguos
            Asiento::where('consecutivo', $consecutivo)
                ->where('empresa_id', $empresaId)
                ->delete();

            $nuevos = [];
            foreach ($request->asientos as $data) {
                $cuentaGlobal = Cuenta::where('codigo', $data['cuenta'])->first();

                if (!$cuentaGlobal) {
                    $cuentaGlobal = Cuenta::create([
                        'codigo' => $data['cuenta'],
                        'nombre' => $data['nombre_cuenta'] ?? 'Cuenta sin nombre'
                    ]);
                }

                $data['nombre_cuenta'] = $data['nombre_cuenta'] ?? $cuentaGlobal->nombre;
                $data['empresa_id'] = $empresaId;
                $data['consecutivo'] = $consecutivo;
                $data['saldo'] = $data['debito'] - $data['credito'];

                if (!isset($data['usuario_creador']) || empty($data['usuario_creador'])) {
                    $data['usuario_creador'] = auth()->user()->nombre_usuario ?? 'Usuario Sistema';
                }

                $nuevos[] = Asiento::create($data);
            }

            DB::commit();

            return response()->json([
                'message' => 'Asiento actualizado correctamente',
                'asientos' => $nuevos
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al actualizar asiento',
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
     * Eliminar por tipo + consecutivo
     */
    public function deleteByTipoConsecutivo($tipo, $consecutivo, Request $request)
    {
        try {
            $empresaId = $request->header('empresa_id');

            $deleted = Asiento::where('empresa_id', $empresaId)
                ->where('tipo', $tipo)
                ->where('consecutivo', $consecutivo)
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
     * Exportar PDF
     */
    public function exportarPdf($tipo, $consecutivo, Request $request)
    {
        $empresaId = $request->header('empresa_id');

        if (!$empresaId) {
            return response()->json(['error' => 'No se ha definido empresa activa'], 422);
        }

        $asientos = Asiento::with(['tercero', 'cuentaInfo', 'cuentaEmpresa'])
            ->where('empresa_id', $empresaId)
            ->where('tipo', $tipo)
            ->where('consecutivo', $consecutivo)
            ->orderBy('id', 'asc')
            ->get();

        if ($asientos->isEmpty()) {
            return response()->json(['error' => 'Asiento no encontrado'], 404);
        }

        $empresa = auth()->user()->empresa;

        $pdf = Pdf::loadView('pdf.asiento', [
            'empresa' => $empresa,
            'asientos' => $asientos,
            'tipo' => $tipo,
            'consecutivo' => $consecutivo,
            'fechaDescarga' => now()->format('d/m/Y H:i'),
        ]);

        $nombreArchivo = "Soporte_Asiento_{$tipo}_{$consecutivo}.pdf";

        return $pdf->download($nombreArchivo);
    }
}
