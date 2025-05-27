<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NombreProducto;
use Illuminate\Validation\ValidationException;

class NombreProductoController extends Controller
{
   
public function save(Request $request)
{
   
    $request->validate([
        'Codigo' => 'required|string', 
        'Nombre' => 'required|string|max:255',
        'Descripcion' => 'required|string|max:500',
        'Precio' => 'required|numeric',
        'Stock' => 'required|integer',
    ]);

    
    $nombre_producto = NombreProducto::create([
        'Codigo' => $request->Codigo, 
        'Nombre' => $request->Nombre, 
        'Descripcion' => $request->Descripcion, 
        'Precio' => $request->Precio, 
        'Stock' => $request->Stock, 
    ]);

    
    return response()->json([
        'status' => '200',
        'message' => 'Producto guardado con éxito',
        'data' => $nombre_producto 
    ]);
}


public function update(Request $request, $id)
{
    
    $request->validate([
        'codigo' => 'required|string', 
        'nombre' => 'required|string|max:255', 
        'descripcion' => 'required|string|max:500', 
        'precio' => 'required|numeric', 
        'stock' => 'required|integer', 
    ]);
        
        $nombre_producto = Nombreproducto::findOrFail($id);

       
        $nombre_producto->update([
            'Codigo' => $request->codigo,
            'Nombre' => $request->nombre,
            'Descripcion' => $request->descripcion,
            'Precio' => $request->precio,
            'Stock' => $request->stock,
        ]);

        
        return response()->json([
            'status' => '200',
            'message' => 'Producto actualizado con éxito',
            'data' => $nombre_producto 
        ]);
    }

    
    public function getData(Request $request)
    {
        
        $productos = Nombreproducto::all(); 

        
        return response()->json([
            'status' => '200',
            'message' => 'Productos solicitados con éxito',
            'data' => $productos,
        ]);
    }

    
    public function getDataById($id)
    {
        try {
            
            $producto = Nombreproducto::findOrFail($id);

            
            return response()->json([
                'status' => '200',
                'message' => 'Producto encontrado',
                'data' => $producto,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            
            return response()->json([
                'status' => '404',
                'message' => 'Producto no encontrado',
            ], 404);
        }
    }

    
    public function delete($id)
    {
        try {
            
            $nombre_producto = Nombreproducto::findOrFail($id);
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
