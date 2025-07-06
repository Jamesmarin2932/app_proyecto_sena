<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cuenta;


class CuentaController extends Controller
{
    public function index()
    {
        return Cuenta::orderBy('codigo')->get();
    }

    public function store(Request $request)
{
    $request->validate([
        'codigo' => 'required|unique:cuentas',
        'nombre' => 'required',
    ]);

    $cuenta = Cuenta::create($request->only(['codigo', 'nombre']));
    return response()->json(['message' => 'Cuenta creada correctamente', 'cuenta' => $cuenta]);
}


    public function update(Request $request, $id)
{
    $request->validate([
        'codigo' => 'required|unique:cuentas,codigo,' . $id,
        'nombre' => 'required',
    ]);

    $cuenta = Cuenta::findOrFail($id);
    $cuenta->update($request->only(['codigo', 'nombre']));

    return response()->json(['message' => 'Cuenta actualizada correctamente']);
}

public function destroy($id)
{
    $cuenta = Cuenta::find($id);

    if (!$cuenta) {
        return response()->json(['error' => 'Cuenta no encontrada'], 404);
    }

    $cuenta->delete();

    return response()->noContent();
}


}