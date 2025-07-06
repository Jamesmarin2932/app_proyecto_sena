<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\DetalleFactura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FacturaController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente_id' => 'required|exists:dato_clientes,id',
            'fecha' => 'required|date',
            'productos' => 'required|array|min:1',
            'productos.*.id_producto' => 'required|integer',
            'productos.*.codigo_del_producto' => 'required|string',
            'productos.*.producto' => 'required|string',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
            'productos.*.descuento' => 'required|numeric|min:0',
            'productos.*.iva' => 'required|numeric|min:0', // porcentaje IVA
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Obtener número de factura consecutivo
            $ultimo = Factura::latest('numero_factura')->first();
            $numero = $ultimo ? intval(substr($ultimo->numero_factura, 3)) + 1 : 1;
            $numeroFactura = 'FV-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

            $productos = $request->input('productos');

            $subTotal = 0;
            $totalIVA = 0;
            $descuentoTotal = 0;
            $totalGeneral = 0;

            foreach ($productos as &$producto) {
                $precio = $producto['precio_unitario'];
                $cantidad = $producto['cantidad'];
                $descuento = $producto['descuento'];
                $ivaPorcentaje = $producto['iva'];

                $base = $precio * $cantidad;
                $ivaCalculado = ($base - $descuento) * ($ivaPorcentaje / 100);
                $total = $base - $descuento + $ivaCalculado;

                $producto['iva'] = round($ivaCalculado, 2); // valor calculado, no porcentaje
                $producto['total'] = round($total, 2);

                $subTotal += $base;
                $descuentoTotal += $descuento;
                $totalIVA += $ivaCalculado;
                $totalGeneral += $total;
            }
            unset($producto); // romper referencia

            // Crear factura
            $factura = Factura::create([
                'numero_factura' => $numeroFactura,
                'cliente_id' => $request->cliente_id,
                'fecha' => $request->fecha,
                'sub_total' => round($subTotal, 2),
                'descuento' => round($descuentoTotal, 2),
                'iva' => round($totalIVA, 2),
                'total' => round($totalGeneral, 2),
            ]);

            // Crear detalles de la factura
            foreach ($productos as $prod) {
                DetalleFactura::create([
                    'factura_id' => $factura->id,
                    'codigo_del_producto' => $prod['codigo_del_producto'],
                    'producto' => $prod['producto'],
                    'cantidad' => $prod['cantidad'],
                    'precio_unitario' => $prod['precio_unitario'],
                    'descuento' => $prod['descuento'],
                    'iva' => $prod['iva'],
                    'total' => $prod['total'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Factura guardada correctamente',
                'factura' => $factura
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => 'Error al guardar la factura',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getNextNumeroFactura()
    {
        $ultimo = Factura::latest('numero_factura')->first();
        $numero = $ultimo ? intval(substr($ultimo->numero_factura, 3)) + 1 : 1;
        $numeroFactura = 'FV-' . str_pad($numero, 5, '0', STR_PAD_LEFT);

        return response()->json([
            'status' => 200,
            'numero_factura' => $numeroFactura
        ]);
    }

    public function update(Request $request)
    {
        $factura = Factura::findOrFail($request->id);
        $factura->update([
            'numero_factura' => $request->numero_factura,
            'cliente_id' => $request->cliente_id,
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Actualizado con éxito',
        ]);
    }

    public function getFacturas()
    {
        $facturas = Factura::with(['cliente', 'detalles'])->get();

        return response()->json([
            'status' => 200,
            'data' => $facturas,
        ]);
    }

    public function delete(Request $request)
    {
        $factura = Factura::findOrFail($request->id);
        $factura->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Eliminado con éxito',
        ]);
    }

    public function byId(Request $request)
    {
        $factura = Factura::with('detalles')->findOrFail($request->id);

        return response()->json([
            'status' => 200,
            'data' => $factura,
        ]);
    }
}
