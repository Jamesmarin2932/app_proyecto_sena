<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatoCliente;

class DatoClienteController extends Controller
{
    /**
     * Guardar un nuevo cliente.
     */
    public function save(Request $request)
    {
        $validated = $request->validate([
            'tipo_identificacion'     => 'required|string|max:10',
            'numero_identificacion'   => 'required|string|max:20|unique:dato_clientes,numero_identificacion',
            'nombres'                 => 'nullable|string|max:100',
            'apellidos'               => 'nullable|string|max:100',
            'razon_social'           => 'nullable|string|max:200',
            'tipo_persona'           => 'required|in:natural,juridica',
            'tipo_tercero'           => 'required|in:cliente,proveedor,ambos',
            'direccion'              => 'required|string|max:255',
            'departamento'           => 'required|string|max:100',
            'ciudad'                 => 'required|string|max:100',
            'codigo_postal'          => 'nullable|string|max:20',
            'pais'                   => 'required|string|max:100',
            'telefono'               => 'nullable|string|max:20',
            'correo'                 => 'nullable|email|max:100',
            'actividad_economica'    => 'nullable|string|max:255',
            'observaciones'          => 'nullable|string|max:500',
            'cuenta_gasto' => 'nullable|string|max:100', // 👈 Aquí también puedes ponerlo como 'required' si deseas

        ]);

        DatoCliente::create($validated);

        return response()->json([
            'status' => '200',
            'message' => 'Guardado con éxito',
        ]);
    }

    /**
     * Actualizar cliente.
     */
    public function update(Request $request, $id)
    {
        $dato_cliente = DatoCliente::findOrFail($id);

        $validated = $request->validate([
            'tipo_identificacion'     => 'required|string|max:10',
            'numero_identificacion'   => 'required|string|max:20|unique:dato_clientes,numero_identificacion,' . $dato_cliente->id,
            'nombres'                 => 'nullable|string|max:100',
            'apellidos'               => 'nullable|string|max:100',
            'razon_social'           => 'nullable|string|max:200',
            'tipo_persona'           => 'required|in:natural,juridica',
            'tipo_tercero'           => 'required|in:cliente,proveedor,ambos',
            'direccion'              => 'required|string|max:255',
            'departamento'           => 'required|string|max:100',
            'ciudad'                 => 'required|string|max:100',
            'codigo_postal'          => 'nullable|string|max:20',
            'pais'                   => 'required|string|max:100',
            'telefono'               => 'nullable|string|max:20',
            'correo'                 => 'nullable|email|max:100',
            'actividad_economica'    => 'nullable|string|max:255',
            'observaciones'          => 'nullable|string|max:500',
            'cuenta_gasto' => 'nullable|string|max:100',


        ]);

        $dato_cliente->update($validated);

        return response()->json([
            'status' => '200',
            'message' => 'Cliente actualizado con éxito',
        ]);
    }

    /**
     * Obtener todos los clientes.
     */
    public function getData()
    {
        $clientes = DatoCliente::all();

        return response()->json([
            'status' => '200',
            'message' => 'Datos solicitados con éxito',
            'data' => $clientes,
        ]);
    }

    /**
     * Obtener un cliente por ID.
     */
    public function getDataById($id)
    {
        try {
            $cliente = DatoCliente::findOrFail($id);

            return response()->json([
                'status' => '200',
                'message' => 'Datos solicitados con éxito',
                'data' => $cliente,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => '404',
                'message' => 'Cliente no encontrado',
            ], 404);
        }
    }

    /**
     * Eliminar cliente.
     */
    public function delete($id)
    {
        $cliente = DatoCliente::findOrFail($id);
        $cliente->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Eliminado con éxito',
        ]);
    }
}
