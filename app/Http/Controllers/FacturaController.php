<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    //
    public function save (Request $request){



        $factura=Factura::create([
            'Numero_de_factura'=> $request->input('Numero_de_factura')
        ]);



       // $factura=new Factura();
       // $factura->Numero_de_factura= $request->Numero_de_factura;
        //$factura->save();
      

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
