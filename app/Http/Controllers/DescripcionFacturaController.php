<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Descripcion_factura;


class DescripcionFacturaController extends Controller
{
    //
    public function save (Request $request){


        $descripcion_factura=Descripcion_factura::create([

            'fecha_de_compra'=> $request->fecha_de_compra,
            'producto'=> $request->producto,
            'cantidad'=> $request->Cantidad,
            'sub_total'=> $request->sub_total,
            'descuento'=> $request->descuento,
            'iva'=> $request->iva,
            'total'=> $request->total,
            'id_factura'=> $request->id_factura,


        ]);

        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


            $descripcion_factura = Descripcion_factura::findOrFail($request->id);
            $descripcion_factura-> update([
                
            'fecha_de_compra'=> $request->fecha_de_compra,
            'producto'=> $request->producto,
            'cantidad'=> $request->Cantidad,
            'sub_total'=> $request->sub_total,
            'descuento'=> $request->descuento,
            'iva'=> $request->iva,
            'total'=> $request->total,
            'id_factura'=> $request->id_factura,
            
            ]);

            
            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); 
        
        }
    

            public function getData(Request $request)
            {
               $descripcion_factura = Descripcion_factura::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' =>$descripcion_factura,
                ]);
            }

        
            public function delete (Request $request){

                    
               $descripcion_factura = Descripcion_factura::findOrFail($request->id);
               $descripcion_factura-> delete();
    
                        return response()->json([
                            'status'=>'200',
                            'message'=> 'Eliminado con exito',
                        ] 
                            
                        ); }

                    public function byid (Request $request){

                        return response()->json([
                            'status'=>'200',
                            'message'=> 'guardado con exito',
                        ]
                            
                        ); }
                
}
