<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ciudad;
class CiudadController extends Controller
{
    //
    public function save (Request $request){

          
        $ciudad = Ciudad::create([
            'ciudad' => $request->ciudad,
            'id_datos_clientes' => $request->id_datos_clientes
        ]);


        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


            $ciudad = Ciudad::findOrFail($request->id);
            $ciudad-> update([
            'ciudad' => $request->ciudad,
            'id_datos_clientes' => $request->id_datos_clientes


            ]);


            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }

            public function getData(Request $request)
            {
               $ciudad=Ciudad::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' =>$ciudad,
                ]);
            }

            public function delete (Request $request){

                    
                $ciudad = Telefono::findOrFail($request->id);
                $ciudad-> delete();
     
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
