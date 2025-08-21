<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return response()->json(Empresa::all(), 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_razon_social' => 'required|string|max:255',
            'nombre_comercial'    => 'nullable|string|max:255',
            'nit'                 => 'nullable|string|max:50',
            'direccion'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:100',
            'departamento'        => 'nullable|string|max:100',
            'pais'                => 'nullable|string|max:100',
            'actividad_economica' => 'nullable|string|max:255',
        ]);

        $empresa = Empresa::create($validated);

        return response()->json([
            'message' => 'Empresa creada correctamente',
            'data' => $empresa
        ], 201);
    }

    public function show(Empresa $empresa)
    {
        return response()->json($empresa, 200);
    }

    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre_razon_social' => 'required|string|max:255',
            'nombre_comercial'    => 'nullable|string|max:255',
            'nit'                 => 'nullable|string|max:50',
            'direccion'           => 'nullable|string|max:255',
            'ciudad'              => 'nullable|string|max:100',
            'departamento'        => 'nullable|string|max:100',
            'pais'                => 'nullable|string|max:100',
            'actividad_economica' => 'nullable|string|max:255',
        ]);

        $empresa->update($validated);

        return response()->json([
            'message' => 'Empresa actualizada correctamente',
            'data' => $empresa
        ], 200);
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return response()->json([
            'message' => 'Empresa eliminada correctamente'
        ], 200);
    }
}
