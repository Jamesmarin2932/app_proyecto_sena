<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dato_producto;
use App\Models\Factura;

class DatoProductoController extends Controller
{
    //
    public function save (Request $request){


        $dato_producto = Dato_Producto::create([
            'codigo' => $request->codigo,
            'id_factura' => $request->id_factura,
        ]);


        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


             $dato_producto = Dato_producto::findOrFail($request->id);
             $dato_producto-> update([
                'codigo'=>$request->codigo,
                'id_factura'=>$request->id_factura,
                


            ]);


            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }

            public function getData(Request $request)
            {
                $dato_producto = Dato_producto::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' => $dato_producto,
                ]);
            }
        
            public function delete (Request $request){

                    
               $dato_producto = Dato_producto::findOrFail($request->id);
               $dato_producto-> delete();
    
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
