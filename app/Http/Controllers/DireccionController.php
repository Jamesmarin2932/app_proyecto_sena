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
