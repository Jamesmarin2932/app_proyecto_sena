<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Direccion;

class DireccionController extends Controller
{
    //

    public function save (Request $request){

        $direccion = Direccion::create([
            'direccion' => $request->direccion,
            'id_datos_clientes' => $request->id_datos_clientes
        ]);


        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){


          $direccion = Direccion::findOrFail($request->id);
          $direccion-> update([
            'direccion' => $request->direccion,
            'id_datos_clientes' => $request->id_datos_clientes


            ]);


            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }

    
            public function getData(Request $request)
            {
                $direccion=Direccion::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' => $direccion,
                ]);
            }
        
            public function delete (Request $request){

                    
                $direccion = Direccion::findOrFail($request->id);
                $direccion-> delete();
     
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
