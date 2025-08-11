<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatoCliente;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatoClienteController extends Controller
{
    /**
     * Guardar un nuevo cliente.
     */
    public function save(Request $request)
{
    $validated = $request->validate([
        'tipo_identificacion'   => 'required|string|max:10',
        'numero_identificacion' => 'required|string|max:20|unique:dato_clientes,numero_identificacion',
        'nombres'               => 'nullable|string|max:100',
        'apellidos'             => 'nullable|string|max:100',
        'razon_social'          => 'nullable|string|max:200',
        'tipo_persona'          => 'required|in:natural,juridica',
        'tipo_tercero'          => 'required|in:cliente,proveedor,ambos',
        'direccion'             => 'required|string|max:255',
        'departamento'          => 'required|string|max:100',
        'ciudad'                => 'required|string|max:100',
        'codigo_postal'         => 'nullable|string|max:20',
        'pais'                  => 'required|string|max:100',
        'telefono'              => 'nullable|string|max:20',
        'correo'                => 'nullable|email|max:100',
        'actividad_economica'   => 'nullable|string|max:255',
        'observaciones'         => 'nullable|string|max:500',
        'cuenta_gasto'          => 'nullable|string|max:100',
    ]);

    // Obtener empresa_id del header o del request
    $empresaId = $request->header('empresa_id') ?? $request->empresa_id;

    if (!$empresaId) {
        return response()->json([
            'status' => 400,
            'message' => 'No se especificó la empresa activa.'
        ], 400);
    }

    DatoCliente::create(array_merge($validated, [
        'empresa_id' => $empresaId,
    ]));

    return response()->json([
        'status'  => 200,
        'message' => 'Cliente guardado con éxito',
    ]);
}


    /**
     * Actualizar cliente.
     */
    public function update(Request $request, $id)
    {
        $empresaId = auth()->user()->empresa_id;

        $datoCliente = DatoCliente::where('empresa_id', $empresaId)->findOrFail($id);

        $validated = $request->validate([
            'tipo_identificacion'   => 'required|string|max:10',
            'numero_identificacion' => 'required|string|max:20|unique:dato_clientes,numero_identificacion,' . $datoCliente->id,
            'nombres'               => 'nullable|string|max:100',
            'apellidos'             => 'nullable|string|max:100',
            'razon_social'          => 'nullable|string|max:200',
            'tipo_persona'          => 'required|in:natural,juridica',
            'tipo_tercero'          => 'required|in:cliente,proveedor,ambos',
            'direccion'             => 'required|string|max:255',
            'departamento'          => 'required|string|max:100',
            'ciudad'                => 'required|string|max:100',
            'codigo_postal'         => 'nullable|string|max:20',
            'pais'                  => 'required|string|max:100',
            'telefono'              => 'nullable|string|max:20',
            'correo'                => 'nullable|email|max:100',
            'actividad_economica'   => 'nullable|string|max:255',
            'observaciones'         => 'nullable|string|max:500',
            'cuenta_gasto'          => 'nullable|string|max:100',
        ]);

        $datoCliente->update($validated);

        return response()->json([
            'status'  => 200,
            'message' => 'Cliente actualizado con éxito',
        ]);
    }

    /**
     * Obtener todos los clientes de la empresa activa.
     */
    public function getData(Request $request)
{
    // Tomar empresa_id del header o del request
    $empresaId = $request->header('empresa_id') ?? $request->empresa_id;

    if (!$empresaId) {
        return response()->json([
            'status'  => 400,
            'message' => 'No se especificó la empresa activa',
            'data'    => []
        ], 400);
    }

    $clientes = DatoCliente::where('empresa_id', $empresaId)->get();

    return response()->json([
        'status'  => 200,
        'message' => 'Datos solicitados con éxito',
        'data'    => $clientes,
    ]);
}
    /**
     * Eliminar cliente (verifica empresa activa).
     */
    public function delete($id)
    {
        $empresaId = auth()->user()->empresa_id;

        $cliente = DatoCliente::where('empresa_id', $empresaId)->findOrFail($id);
        $cliente->delete();

        return response()->json([
            'status'  => 200,
            'message' => 'Cliente eliminado con éxito',
        ]);
    }
}
