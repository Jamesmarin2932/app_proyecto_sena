<?php

namespace App\Http\Controllers;

use App\Models\Descripcion_factura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class DescripcionFacturaController extends Controller
{
    public function save(Request $request)
    {
        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'tipo_identificacion' => 'required|string|max:255',
            'numero_identificacion' => 'required|string|max:255',
            'cliente' => 'required|string|max:255',
            'fecha' => 'required|date',
            'codigo_del_producto' => 'required|string|max:255',
            'producto' => 'required|string|max:255',
            'cantidad' => 'required|integer|min:1',
            'precio_unitario' => 'required|numeric|min:0',
            'sub_total' => 'required|numeric|min:0',
            'descuento' => 'required|numeric|min:0',
            'iva' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 400);
        }

        try {
            
            $lastInvoice = Descripcion_factura::orderBy('created_at', 'desc')->first();

          
$secuencia = 1; 


if ($lastInvoice) {
    
    $lastConsecutiveNumber = substr($lastInvoice->numero_factura, 3); 
    
    if (is_numeric($lastConsecutiveNumber)) {
        $secuencia = intval($lastConsecutiveNumber) + 1; 
    }
}

$numeroFactura = 'FV-' . str_pad($secuencia, 5, '0', STR_PAD_LEFT); 

            // Crear la factura
            $factura = new Descripcion_factura();
            $factura->tipo_identificacion = $request->tipo_identificacion;
            $factura->numero_identificacion = $request->numero_identificacion;
            $factura->cliente = $request->cliente;
            $factura->fecha = $request->fecha;
            $factura->codigo_del_producto = $request->codigo_del_producto;
            $factura->producto = $request->producto;
            $factura->cantidad = $request->cantidad;
            $factura->precio_unitario = $request->precio_unitario;
            $factura->sub_total = $request->sub_total;
            $factura->descuento = $request->descuento;
            $factura->iva = $request->iva;
            $factura->total = $request->total;
            $factura->numero_factura = $numeroFactura; // Asignar el número de factura generado
            $factura->save();

           
            return response()->json([
                'status' => 200,
                'message' => 'Factura guardada con éxito',
                'data' => $factura, 
            ], 200);
        } catch (Exception $e) {
            
            return response()->json([
                'status' => 500,
                'message' => 'Hubo un error al guardar la factura',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function update(Request $request)
    {
       
        $descripcion_factura = Descripcion_factura::findOrFail($request->id);
        $descripcion_factura->update([
            'fecha' => $request->fecha,
            'producto' => $request->producto,
            'cantidad' => $request->cantidad,
            'sub_total' => $request->sub_total,
            'descuento' => $request->descuento,
            'iva' => $request->iva,
            'total' => $request->total,
            'numero_factura' => $request->numero_factura, 
        ]);

        return response()->json([
            'status' => '200',
            'message' => 'Factura actualizada con éxito',
        ]);
    }

    public function getData(Request $request)
    {
        $descripcion_factura = Descripcion_factura::all();

        return response()->json([
            'status' => '200',
            'message' => 'Datos solicitados con éxito',
            'data' => $descripcion_factura,
        ]);
    }

    public function delete(Request $request)
    {
        // Eliminar una factura por ID
        $descripcion_factura = Descripcion_factura::findOrFail($request->id);
        $descripcion_factura->delete();

        return response()->json([
            'status' => '200',
            'message' => 'Factura eliminada con éxito',
        ]);
    }

    public function byid(Request $request)
    {
       
        return response()->json([
            'status' => '200',
            'message' => 'Datos obtenidos con éxito',
        ]);
    }
}
