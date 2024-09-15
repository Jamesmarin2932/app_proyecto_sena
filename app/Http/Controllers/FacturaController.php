<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Factura;

class FacturaController extends Controller
{
    //
    public function save (Request $request){



        $factura = Factura::create([
            'Numero_de_factura' => $request->Numero_de_factura,
            'id_cliente' => $request->id_cliente
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

public function delete(Request $request)
{
    // Busca la factura por el número
    $factura = Factura::where('Numero_de_factura', $request->Numero_de_factura)->first();
    
    // Verifica si la factura existe
    if ($factura) {
        // Elimina la factura
        $factura->delete();

        // Retorna una respuesta de éxito
        return response()->json([
            'status' => '200',
            'message' => 'Eliminado con éxito',
        ]);
    } else {
        // Si la factura no existe, retorna un error
        return response()->json([
            'status' => '404',
            'message' => 'Factura no encontrada',
        ]);
    }
}

            

                    public function byid (Request $request){

                        return response()->json([
                            'status'=>'200',
                            'message'=> 'guardado con exito',
                        ]
                            
                        ); }
                


}
