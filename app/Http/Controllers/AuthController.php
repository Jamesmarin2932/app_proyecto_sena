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
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
    
        $user = User::where('usuario', $request->usuario)->first();
    
        // 👇 Asegúrate de que esté así
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas.'], 401);
        }
    
        $token = $user->createToken('API Token')->plainTextToken;
    
        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token' => $token,
            'username' => $user->usuario,
        ]);
    }
    
}
