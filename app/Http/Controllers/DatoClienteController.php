<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dato_cliente;


class DatoClienteController extends Controller
{
    //

    public function save (Request $request){

        $dato_cliente=Dato_cliente::create([

        'tipo_identificacion' => $request->tipo_identificacion,
        'numero_identificacion' => $request->numero_identificacion,
        'nombres' => $request->nombres,
        'apellidos' => $request->apellidos,
        'direccion' => $request->direccion,
        'ciudad' => $request->ciudad,
        'telefono' => $request->telefono,
        'correo' => $request->correo,
        
        ]);

      

        return response()->json([
            'status'=>'200',
            'message'=> 'guardado con exito',
            
        ]
            
        ); }
        public function update(Request $request, $id)
        {
            // Buscar el cliente por el ID proporcionado
            $dato_cliente = Dato_cliente::findOrFail($id);
        
            // Actualizar los campos específicos del cliente
            $dato_cliente->tipo_identificacion = $request->tipo_identificacion;
            $dato_cliente->numero_identificacion = $request->numero_identificacion;
            $dato_cliente->nombres = $request->nombres;
            $dato_cliente->apellidos = $request->apellidos;
            $dato_cliente->direccion = $request->direccion;
            $dato_cliente->ciudad = $request->ciudad;
            $dato_cliente->telefono = $request->telefono;
            $dato_cliente->correo = $request->correo;
        
            // Guardar los cambios en la base de datos
            $dato_cliente->save();
        
            return response()->json([
                'status' => '200',
                'message' => 'Cliente actualizado con éxito',
            ]);
        }
        

            public function getData(Request $request)
            {
                $clientes = Dato_cliente::all();
        
                return response()->json([
                    'status' => '200',
                    'message' => 'Datos solicitados con éxito',
                    'data' => $clientes,
                ]);
            }

            public function getDataById($id)
{
    try {
        $clientes = Dato_cliente::findOrFail($id);
        
        return response()->json([
            'status' => '200',
            'message' => 'Datos solicitados con éxito',
            'data' => $clientes,
        ]);
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'status' => '404',
            'message' => 'Cliente no encontrado',
        ], 404);
    }
}




            public function delete($id) {  // Aquí se recibe el parámetro ID de la URL
                $dato_cliente = Dato_cliente::findOrFail($id);  // Ahora usamos el ID directamente
                $dato_cliente->delete();
            
                return response()->json([
                    'status' => '200',
                    'message' => 'Eliminado con éxito',
                ]);
            }
            
            

                    public function byid (Request $request){

                        return response()->json([
                            'status'=>'200',
                            'message'=> 'guardado con exito',
                        ]
                            
                        ); }
                


}



