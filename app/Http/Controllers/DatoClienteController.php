<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dato_cliente;


class DatoClienteController extends Controller
{
    //

    public function save (Request $request){

        $dato_cliente=Dato_cliente::create([
            'identificacion'=> $request->identificacion,
            'nombre'=> $request->nombre,
            'apellido'=> $request->apellido,

        ]);

      

        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }

        public function update (Request $request){

            return response()->json([
                'status'=>'200',
                'message'=> 'Actualizado con exito',
            ]
                
            ); }
    

            public function getdata(Request $request)
{
    return response()->json([
        'status' => '200',
        'message' => 'solicitado con éxito',
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



