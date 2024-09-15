<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nombre_producto;

class NombreProductoController extends Controller
{
    //
    public function save (Request $request){


        $nombre_producto=Nombre_producto::create([

            'Nombre'=> $request->Nombre,
            'Descripcion'=> $request->Descripcion,
            'Precio'=> $request->Precio,
            'Stock'=> $request->Stock,
            'id_datos_productos'=> $request->id_datos_productos,
           

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
