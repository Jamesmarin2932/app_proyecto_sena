<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nombre_producto;
use Illuminate\Validation\ValidationException;

class NombreProductoController extends Controller
{
   // Método para guardar un producto
public function save(Request $request)
{
    // Validación de los datos
    $request->validate([
        'codigo' => 'required|string', // Sólo asegurarte de que es un string
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string|max:500',
        'precio' => 'required|numeric',
        'stock' => 'required|integer',
    ]);

    // Crear un nuevo registro de producto
    $nombre_producto = Nombre_producto::create([
        'Codigo' => $request->codigo, // Guardar el código proporcionado
        'Nombre' => $request->nombre, // Guardar el nombre del producto
        'Descripcion' => $request->descripcion, // Guardar la descripción
        'Precio' => $request->precio, // Guardar el precio
        'Stock' => $request->stock, // Guardar la cantidad en stock
    ]);

    // Responder con éxito
    return response()->json([
        'status' => '200',
        'message' => 'Producto guardado con éxito',
        'data' => $nombre_producto // Retornar el producto recién creado
    ]);
}

// Método para actualizar un producto existente
public function update(Request $request, $id)
{
    // Validación de los datos
    $request->validate([
        'codigo' => 'required|string', // Validar que el código no esté vacío, sin restricciones de longitud
        'nombre' => 'required|string|max:255', // Nombre obligatorio con un máximo de 255 caracteres
        'descripcion' => 'required|string|max:500', // Descripción obligatoria, con un máximo de 500 caracteres
        'precio' => 'required|numeric', // Precio debe ser numérico
        'stock' => 'required|integer', // Stock debe ser un número entero
    ]);
        // Buscar el producto por ID
        $nombre_producto = Nombre_producto::findOrFail($id);

        // Actualizar los datos del producto
        $nombre_producto->update([
            'Codigo' => $request->codigo,
            'Nombre' => $request->nombre,
            'Descripcion' => $request->descripcion,
            'Precio' => $request->precio,
            'Stock' => $request->stock,
        ]);

        // Responder con éxito
        return response()->json([
            'status' => '200',
            'message' => 'Producto actualizado con éxito',
            'data' => $nombre_producto // Retornar los datos actualizados
        ]);
    }

    // Método para obtener todos los productos
    public function getData(Request $request)
    {
        // Obtener todos los productos con paginación
        $productos = Nombre_producto::all(); 

        // Responder con los productos
        return response()->json([
            'status' => '200',
            'message' => 'Productos solicitados con éxito',
            'data' => $productos,
        ]);
    }

    // Método para obtener un producto por ID
    public function getDataById($id)
    {
        try {
            // Buscar el producto por ID
            $producto = Nombre_producto::findOrFail($id);

            // Responder con los datos del producto
            return response()->json([
                'status' => '200',
                'message' => 'Producto encontrado',
                'data' => $producto,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Responder con error si el producto no se encuentra
            return response()->json([
                'status' => '404',
                'message' => 'Producto no encontrado',
            ], 404);
        }
    }

    // Método para eliminar un producto
    public function delete($id)
    {
        try {
            // Buscar el producto por ID
            $nombre_producto = Nombre_producto::findOrFail($id);
            $nombre_producto->delete();

            return response()->json([
                'status' => '200',
                'message' => 'Producto eliminado con éxito',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => '404',
                'message' => 'Producto no encontrado',
            ], 404);
        }
    }
}
