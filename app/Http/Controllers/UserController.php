<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Registrar un nuevo usuario
     */
    public function register(Request $request)
{
    $request->validate([
        'nombre_usuario' => 'required|string|max:255',
        'usuario' => 'required|string|max:255|unique:users,usuario',
        'identificacion' => 'required|string|max:20|unique:users,identificacion',
        'password' => 'required|string|min:6|confirmed',
        'empresas' => 'required|array',
        'empresas.*' => 'exists:empresas,id',
    ]);

    $user = User::create([
        'nombre_usuario' => $request->nombre_usuario,
        'usuario' => $request->usuario,
        'identificacion' => $request->identificacion,
        'password' => $request->password,
    ]);

    // Asociar empresas
    $user->empresas()->sync($request->empresas);

    return response()->json([
        'message' => 'Usuario creado exitosamente',
        'user' => $user
    ], 201);
}



    /**
     * Obtener todos los usuarios (incluyendo roles y empresas)
     */
   public function index()
{
    try {
        // ✅ SOLUCIÓN: Cargar solo la relación 'empresas' que SÍ existe
        $users = User::with('empresas')->get();
        
        return response()->json(['users' => $users], 200);
        
    } catch (\Exception $e) {
        \Log::error('Error en UserController@index: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor'], 500);
    }
}
    /**
     * Mostrar un usuario por ID
     */
   public function show($id)
{
    try {
        $user = User::with('empresas')->find($id);
        
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['user' => $user], 200);
        
    } catch (\Exception $e) {
        \Log::error('Error en UserController@show: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno del servidor'], 500);
    }
}

    /**
     * Eliminar un usuario
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Eliminar relación con empresas
        $user->empresas()->detach();

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }

    /**
     * Actualizar un usuario
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'sometimes|required|string|max:255',
            'identificacion' => 'sometimes|required|string|unique:users,identificacion,' . $id,
            'usuario'        => 'sometimes|required|string|unique:users,usuario,' . $id,
            'password'       => 'nullable|string|min:4|confirmed',
            'empresas'       => 'array',
            'empresas.*'     => 'exists:empresas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->nombre_usuario = $request->nombre_usuario ?? $user->nombre_usuario;
        $user->identificacion = $request->identificacion ?? $user->identificacion;
        $user->usuario        = $request->usuario ?? $user->usuario;

        if (!empty($request->password)) {
    $user->password = $request->password; // el mutador lo hashea
}


        $user->save();

        // Actualizar empresas
        if ($request->has('empresas')) {
            $user->empresas()->sync($request->empresas);
        }

        return response()->json([
            'message' => 'Usuario actualizado con éxito.',
            'user' => $user->load('empresas')
        ], 200);
    }
}
