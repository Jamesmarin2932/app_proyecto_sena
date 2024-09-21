<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Correo;

class CorreoController extends Controller
{
    //
    public function save (Request $request){

         
        $correo = Correo::create([
            'correo' => $request->correo,
            'id_datos_clientes' => $request->id_datos_clientes
        ]);



        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


             $correo = Correo::findOrFail($request->id);
             $correo-> update([
            'correo' => $request->correo,
            'id_datos_clientes' => $request->id_datos_clientes


            ]);


            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }

    

            public function getData(Request $request)
            {
               $correo=Correo::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' =>$correo,
                ]);
            }
        
                public function delete (Request $request){

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
