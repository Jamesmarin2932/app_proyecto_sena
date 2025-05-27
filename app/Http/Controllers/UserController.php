<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        // Validar los datos recibidos
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'identificacion' => 'required|string|unique:users,identificacion',
            'usuario' => 'required|string|unique:users,usuario',
            'password' => 'required|string|min:4|confirmed', // La contraseña debe tener al menos 4 caracteres
            'password_confirmation' => 'required|string|min:4', // Asegura que la confirmación de la contraseña esté incluida
        ]);

        // Si la validación falla, devolver los errores
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el nuevo usuario
        $user = User::create([
            'nombre_usuario' => $request->nombre_usuario,
            'identificacion' => $request->identificacion,
            'usuario' => $request->usuario,
            'password' => $request->password, // sin bcrypt, el mutador lo maneja
        ]);

        // Devolver una respuesta exitosa
        return response()->json(['message' => 'Usuario registrado con éxito.', 'user' => $user], 201);
    }

    // Método para obtener la lista de usuarios
    public function index()
    {
        $users = User::all(); // Obtiene todos los usuarios de la base de datos

        return response()->json(['users' => $users], 200); // Retorna la lista de usuarios en formato JSON
    }

    // Método para obtener un usuario por su ID
    public function show($id)
    {
        // Buscar el usuario por ID
        $user = User::find($id);

        // Si el usuario no existe, retornar un error
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        return response()->json(['user' => $user], 200);
    }

    // Método para eliminar un usuario
    public function destroy($id)
    {
        // Buscar el usuario por ID
        $user = User::find($id);

        // Si el usuario no existe, retornar un error
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Eliminar el usuario
        $user->delete();

        // Retornar una respuesta exitosa
        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }


    // Método para actualizar un usuario
public function update(Request $request, $id)
{
    // Buscar el usuario
    $user = User::find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }

    // Validar los datos recibidos
    $validator = Validator::make($request->all(), [
        'nombre_usuario' => 'sometimes|required|string|max:255',
        'identificacion' => 'sometimes|required|string|unique:users,identificacion,' . $id,
        'usuario' => 'sometimes|required|string|unique:users,usuario,' . $id,
        'password' => 'nullable|string|min:4|confirmed', // Opcional si quiere cambiar contraseña
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    // Actualizar datos del usuario
    $user->nombre_usuario = $request->nombre_usuario ?? $user->nombre_usuario;
    $user->identificacion = $request->identificacion ?? $user->identificacion;
    $user->usuario = $request->usuario ?? $user->usuario;

    // Solo si se proporciona una nueva contraseña
    if (!empty($request->password)) {
        $user->password = $request->password; // El mutador en el modelo se encarga de encriptarla
    }

    $user->save();

    return response()->json(['message' => 'Usuario actualizado con éxito.', 'user' => $user], 200);
}

}
