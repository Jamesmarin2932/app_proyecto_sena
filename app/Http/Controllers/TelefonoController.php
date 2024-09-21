<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\telefono;

class TelefonoController extends Controller
{
    //
    public function save (Request $request){

        
        $telefono = Telefono::create([
            'telefono' => $request->telefono,
            'id_datos_clientes' => $request->id_datos_clientes
        ]);


        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


            $telefono = Telefono::findOrFail($request->id);
            $telefono-> update([
            'telefono' => $request->telefono,
            'id_datos_clientes' => $request->id_datos_clientes


            ]);


            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }

    
            public function getData(Request $request)
            {
               $telefono=Telefono::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' =>$telefono,
                ]);
            }
            public function delete (Request $request){

                    
                $telefono = Telefono::findOrFail($request->id);
                $telefono-> delete();
     
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
