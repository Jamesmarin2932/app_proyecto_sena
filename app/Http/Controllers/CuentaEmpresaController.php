<?php

namespace App\Http\Controllers;

use App\Models\Cuenta;
use App\Models\CuentaEmpresa;
use Illuminate\Http\Request;

class CuentaEmpresaController extends Controller
{
    // ✅ Obtener solo las cuentas de la empresa
    public function index($empresaId)
    {
        return CuentaEmpresa::where('empresa_id', $empresaId)->get();
    }

    // ✅ Obtener todas las cuentas (globales + de la empresa)
   public function todas($empresaId)
{
    // Cuentas globales
    $globales = \App\Models\Cuenta::select('id', 'codigo', 'nombre')
        ->orderBy('codigo')
        ->get()
        ->map(function ($c) {
            return [
                'id' => $c->id,          // ID real
                'codigo' => $c->codigo,
                'nombre' => $c->nombre,
                'origen' => 'global'     // 👈 indicador de origen
            ];
        });

    // Cuentas de la empresa
    $locales = \App\Models\CuentaEmpresa::where('empresa_id', $empresaId)
        ->select('id', 'codigo', 'nombre')
        ->orderBy('codigo')
        ->get()
        ->map(function ($c) {
            return [
                'id' => $c->id,          // ID real
                'codigo' => $c->codigo,
                'nombre' => $c->nombre,
                'origen' => 'empresa'    // 👈 indicador de origen
            ];
        });

    // Unir ambas colecciones
    return response()->json($globales->merge($locales));
}
    // ✅ Crear cuenta para empresa
    public function store(Request $request, $empresaId)
    {
        $request->validate([
            'codigo' => 'required|string|max:20',
            'nombre' => 'required|string|max:255',
        ]);

        $cuenta = CuentaEmpresa::create([
            'empresa_id' => $empresaId,
            'codigo'     => $request->codigo,
            'nombre'     => $request->nombre,
        ]);

        return response()->json($cuenta, 201);
    }

    // ✅ Mostrar cuenta específica
    public function show($empresaId, $id)
    {
        return CuentaEmpresa::where('empresa_id', $empresaId)->findOrFail($id);
    }

    // ✅ Actualizar cuenta de empresa
    public function update(Request $request, $empresaId, $id)
    {
        $cuenta = CuentaEmpresa::where('empresa_id', $empresaId)->findOrFail($id);

        $cuenta->update($request->only('codigo', 'nombre'));

        return response()->json($cuenta);
    }

    // ✅ Eliminar cuenta de empresa
    public function destroy($empresaId, $id)
    {
        $cuenta = CuentaEmpresa::where('empresa_id', $empresaId)->findOrFail($id);
        $cuenta->delete();

        return response()->json(null, 204);
    }
}

