<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Empresa;

class EmpresaController extends Controller
{
    // Constructor sin middleware para que no pida autenticación
    public function __construct()
    {
        // Middleware desactivado temporalmente
        // $this->middleware('auth:sanctum');
    }

    // Listar todas las empresas
    public function index()
    {
        return Empresa::all();
    }

    // Guardar nueva empresa
    public function store(Request $request)
    {
        // Validación básica
        $request->validate([
            'nombre' => 'required|string|max:255',
            'nit' => 'nullable|string|max:50',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
            'correo' => 'nullable|email|max:255',
        ]);

        // Crear empresa
        $empresa = Empresa::create([
            'nombre' => $request->nombre,
            'nit' => $request->nit,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
        ]);

        return response()->json([
            'message' => 'Empresa creada exitosamente',
            'data' => $empresa
        ], 201);
    }

    // Mostrar una empresa por ID
    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        return $empresa;
    }

    // Actualizar empresa
    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->update($request->all());

        return response()->json([
            'message' => 'Empresa actualizada exitosamente',
            'data' => $empresa
        ]);
    }

    // Eliminar empresa
    public function destroy($id)
    {
        Empresa::findOrFail($id)->delete();
        return response()->json(['message' => 'Empresa eliminada correctamente']);
    }
}
