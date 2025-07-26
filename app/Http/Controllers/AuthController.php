<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 🐛 Log temporal para verificar los datos recibidos
        Log::info('Datos recibidos en login:', $request->all());

        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Buscar usuario por el campo 'usuario'
        $user = User::where('usuario', $request->usuario)->first();

        // Log para ver si el usuario fue encontrado
        if (!$user) {
            Log::warning('Usuario no encontrado: ' . $request->usuario);
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Verificar contraseña
        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Contraseña inválida para usuario: ' . $request->usuario);
            return response()->json(['message' => 'Contraseña incorrecta'], 401);
        }

        // Crear token con Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info('Inicio de sesión exitoso para usuario: ' . $user->usuario);

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'username' => $user->usuario,
        ]);
    }
}
