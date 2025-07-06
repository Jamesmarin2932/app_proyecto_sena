<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'required|string|max:255',
            'identificacion' => 'required|string|unique:users,identificacion',
            'usuario' => 'required|string|unique:users,usuario',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required|string|min:4',
            'rol' => 'required|string|exists:roles,name', // ✅ validamos el nombre del rol
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el nuevo usuario
        $user = User::create([
            'nombre_usuario' => $request->nombre_usuario,
            'identificacion' => $request->identificacion,
            'usuario' => $request->usuario,
            'password' => $request->password,
        ]);

        // ✅ Asignar rol
        $user->assignRole($request->rol);

        return response()->json(['message' => 'Usuario registrado con éxito.', 'user' => $user], 201);
    }

    public function index()
    {
        $users = User::with('roles')->get(); // ✅ incluir roles
        return response()->json(['users' => $users], 200);
    }

    public function show($id)
    {
        $user = User::with('roles')->find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json(['user' => $user], 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre_usuario' => 'sometimes|required|string|max:255',
            'identificacion' => 'sometimes|required|string|unique:users,identificacion,' . $id,
            'usuario' => 'sometimes|required|string|unique:users,usuario,' . $id,
            'password' => 'nullable|string|min:4|confirmed',
            'rol' => 'required|string|exists:roles,name', // ✅ validar el rol
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->nombre_usuario = $request->nombre_usuario ?? $user->nombre_usuario;
        $user->identificacion = $request->identificacion ?? $user->identificacion;
        $user->usuario = $request->usuario ?? $user->usuario;

        if (!empty($request->password)) {
            $user->password = $request->password;
        }

        $user->save();

        // ✅ Actualizar rol
        $user->syncRoles([$request->rol]);

        return response()->json(['message' => 'Usuario actualizado con éxito.', 'user' => $user], 200);
    }

    // ✅ Método para devolver todos los roles disponibles
    public function roles()
    {
        return response()->json(Role::pluck('name'), 200);
    }
}
