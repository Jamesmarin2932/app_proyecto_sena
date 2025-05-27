<?php

namespace App\Http\Controllers;

use App\Models\Asiento;
use App\Models\Cuenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsientoController extends Controller
{
    // Función para guardar los asientos
    public function save(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'asientos' => 'required|array',
            'asientos.*.tipo' => 'required|string',
            'asientos.*.fecha' => 'required|date',
            'asientos.*.factura' => 'required|string',
            'asientos.*.tercero' => 'required|string',
            'asientos.*.cuenta' => 'required|string',
            'asientos.*.concepto' => 'required|string',
            'asientos.*.debito' => 'required|numeric|min:0',
            'asientos.*.credito' => 'required|numeric|min:0',
            'asientos.*.consecutivo' => 'nullable|numeric',  // El consecutivo es opcional
        ]);

        DB::beginTransaction(); // Iniciamos la transacción para asegurar consistencia
        try {
            foreach ($validatedData['asientos'] as $datos) {
                // Si no hay consecutivo, lo generamos automáticamente sumando 1 al último consecutivo.
                $consecutivo = $datos['consecutivo'] ?? ((Asiento::max('consecutivo') ?? 0) + 1);

                // Crear un nuevo asiento contable
                $asiento = new Asiento($datos);
                $asiento->consecutivo = $consecutivo;
                $asiento->saldo = $datos['debito'] - $datos['credito']; // Calculamos el saldo
                $asiento->save();

                // Aseguramos que la cuenta exista, pero no actualizamos el saldo aquí
                $cuenta = Cuenta::firstOrCreate(['codigo' => $datos['cuenta']]);
            }

            DB::commit();  // Si todo va bien, hacemos commit de la transacción
            return response()->json(['message' => 'Asientos guardados correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();  // En caso de error, revertimos todo
            return response()->json(['error' => 'Error al guardar asientos', 'detalle' => $e->getMessage()], 500);
        }
    }

    // Función para obtener todos los asientos
    public function index()
    {
        $asientos = Asiento::orderBy('consecutivo', 'asc')->get();

        // Añadir saldo actual de la cuenta a cada asiento si es necesario
        foreach ($asientos as $asiento) {
            $cuenta = \App\Models\Cuenta::where('codigo', $asiento->cuenta)->first();
            $asiento->saldo_actual = $cuenta ? $cuenta->saldo : 0;  // Definir el saldo actual si existe la cuenta
        }

        return response()->json($asientos);  // Devolvemos los asientos como respuesta JSON
    }

    // Función para obtener el último consecutivo usado
    public function ultimoConsecutivo()
    {
        $ultimo = Asiento::max('consecutivo') ?? 0;  // Obtenemos el último consecutivo o 0 si no hay
        return response()->json(['ultimo' => $ultimo]);
    }
}
