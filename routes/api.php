<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DatoClienteController;
use App\Http\Controllers\DatoProductoController;
use App\Http\Controllers\DescripcionFacturaController;
use App\Http\Controllers\NombreProductoController;
use App\Http\Controllers\TelefonoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\CuentaController;





/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/factura/getdata', [FacturaController::class, 'getdata']);
Route::post('/factura/save', [FacturaController::class, 'save']);
Route::put('/factura/update', [FacturaController::class, 'update']);
Route::delete('/factura/delete', [FacturaController::class, 'delete']);

Route::get('/dato_clientes/getdata', [DatoClienteController::class, 'getdata']);
Route::get('/dato_clientes/getdataById/{id}', [DatoClienteController::class, 'getDataById']);
Route::post('/dato_clientes/save', [DatoClienteController::class, 'save']);
Route::put('/dato_clientes/update/{id}', [DatoClienteController::class, 'update']);
Route::delete('/dato_clientes/delete/{id}', [DatoClienteController::class, 'delete']);

Route::get('/dato_productos/getdata', [DatoProductoController::class, 'getdata']);
Route::post('/dato_productos/save', [DatoProductoController::class, 'save']);
Route::put('/dato_productos/update', [DatoProductoController::class, 'update']);
Route::delete('/dato_productos/delete', [DatoProductoController::class, 'delete']);

Route::get('/descripcion_facturas/getdata', [DescripcionFacturaController::class, 'getdata']);
Route::get('/descripcion_facturas/getdataById/{id}', [DescripcionFacturaController::class, 'byid']);
Route::post('/descripcion_facturas/save', [DescripcionFacturaController::class, 'save']);
Route::put('/descripcion_facturas/update/{id}', [DescripcionFacturaController::class, 'update']);
Route::delete('/descripcion_facturas/delete', [DescripcionFacturaController::class, 'delete']);

Route::get('/nombre_productos/getdata', [NombreProductoController::class, 'getData']);
Route::get('/nombre_productos/getdataById/{id}', [NombreProductoController::class, 'getDataById']); // Añadido para obtener un producto por ID
Route::post('/nombre_productos/save', [NombreProductoController::class, 'save']);
Route::put('/nombre_productos/update/{id}', [NombreProductoController::class, 'update']);
Route::delete('/nombre_productos/delete/{id}', [NombreProductoController::class, 'delete']);


Route::get('/telefonos/getdata', [TelefonoController::class, 'getdata']);
Route::post('/telefonos/save', [TelefonoController::class, 'save']);
Route::put('/telefonos/update', [TelefonoController::class, 'update']);
Route::delete('/telefonos/delete', [TelefonoController::class, 'delete']);

Route::get('/correos/getdata', [CorreoController::class, 'getdata']);
Route::post('/correos/save', [CorreoController::class, 'save']);
Route::put('/correos/update', [CorreoController::class, 'update']);
Route::delete('/correos/delete', [CorreoController::class, 'delete']);

Route::get('/ciudads/getdata', [CiudadController::class, 'getdata']);
Route::post('/ciudads/save', [CiudadController::class, 'save']);
Route::put('/ciudads/update', [CiudadController::class, 'update']);
Route::delete('/ciudads/delete', [CiudadController::class, 'delete']);



Route::post('/users/register', [UserController::class, 'register']); 
Route::put('/users/update/{id}', [UserController::class, 'update']);
Route::get('usuarios', [UserController::class, 'index']); // Listar usuarios
Route::get('usuarios/{id}', [UserController::class, 'show']); // Obtener un usuario por ID
Route::delete('usuarios/{id}', [UserController::class, 'destroy']); // Eliminar un usuario

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('/asientos', [AsientoController::class, 'index']);
Route::post('/asientos', [AsientoController::class, 'save']);
Route::get('/asientos/ultimo-consecutivo', [AsientoController::class, 'ultimoConsecutivo']);

Route::apiResource('cuentas', CuentaController::class);
Route::get('/cuentas-contables', [CuentaController::class, 'index']);

