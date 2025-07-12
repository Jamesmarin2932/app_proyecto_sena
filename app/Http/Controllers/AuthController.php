<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Método para autenticar al usuario y devolver un token
    
   public function login(Request $request)
{
    $usuario = $request->input('usuario');
    $password = $request->input('password');

    // ✅ Usuario fijo para pruebas (NO requiere base de datos)
    if ($usuario === 'admin' && $password === 'admin123') {
        return response()->json([
            'message' => 'Inicio de sesión exitoso (modo prueba).',
            'token' => base64_encode('token-de-prueba'), // puedes usar Str::random(60) si deseas
            'username' => $usuario,
        ]);
    }

    return response()->json(['message' => 'Credenciales inválidas'], 401);
}
}
