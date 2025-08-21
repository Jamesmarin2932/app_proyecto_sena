<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EstablecerEmpresaActiva
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
public function handle($request, Closure $next)
{
    // Rutas que no requieren empresa
    if (
    $request->is('api/login') || 
    $request->is('api/seleccionar-empresa') ||
    $request->is('api/empresas*') ||    // 👈 permite acceso a empresas
    $request->is('api/mis-empresas')
) {
    return $next($request);
}

    $empresaId = $request->header('empresa_id') ?? $request->empresa_id;

    // Validar que el usuario tenga acceso a esa empresa
    if (!$empresaId || !auth()->user()->empresas()->where('empresas.id', $empresaId)->exists()) {
        return response()->json(['error' => 'No tienes acceso a esta empresa'], 403);
    }

    return $next($request);
}
}
