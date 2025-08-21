<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
  // App\Http\Controllers\AuthController.php
public function login(Request $request)
{
    $request->validate([
        'usuario' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('usuario', $request->usuario)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['error' => 'Credenciales incorrectas'], 401);
    }

    // Cargar empresas del usuario
    $empresas = $user->empresas;

    if ($empresas->isEmpty()) {
        return response()->json(['error' => 'Usuario no tiene empresas asignadas'], 403);
    }

    $token = $user->createToken('auth_token')->plainTextToken;

    // ✅ Determinar redirección automáticamente
    $empresaActiva = null;
    $redirectTo = '/seleccionar-empresa';
    
    if ($empresas->count() === 1) {
        // Solo una empresa - selección automática
        $empresaActiva = $empresas->first();
        $redirectTo = '/home';
    }
    
    return response()->json([
        'token' => $token,
        'user' => $user,
        'empresas' => $empresas,
        'redirect_to' => $redirectTo,
        'empresa_activa' => $empresaActiva,
    ], 200);
}
}
