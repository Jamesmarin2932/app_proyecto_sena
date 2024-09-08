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
use App\Http\Controllers\DireccionController;




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
Route::post('/dato_clientes/save', [DatoClienteController::class, 'save']);
Route::put('/dato_clientes/update', [DatoClienteController::class, 'update']);
Route::delete('/dato_clientes/delete', [DatoClienteController::class, 'delete']);

Route::get('/dato_productos/getdata', [DatoProductoController::class, 'getdata']);
Route::post('/dato_productos/save', [DatoProductoController::class, 'save']);
Route::put('/dato_productos/update', [DatoProductoController::class, 'update']);
Route::delete('/dato_productos/delete', [DatoProductoController::class, 'delete']);

Route::get('/descripcion_facturas/getdata', [DescripcionFacturaController::class, 'getdata']);
Route::post('/descripcion_facturas/save', [DescripcionFacturaController::class, 'save']);
Route::put('/descripcion_facturas/update', [DescripcionFacturaController::class, 'update']);
Route::delete('/descripcion_facturas/delete', [DescripcionFacturaController::class, 'delete']);

Route::get('/nombre_productos/getdata', [NombreProductoController::class, 'getdata']);
Route::post('/nombre_productos/save', [NombreProductoController::class, 'save']);
Route::put('/nombre_productos/update', [NombreProductoController::class, 'update']);
Route::delete('/nombre_productos/delete', [NombreProductoController::class, 'delete']);

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

Route::get('/direccions/getdata', [DireccionController::class, 'getdata']);
Route::post('/direccions/save', [DireccionController::class, 'save']);
Route::put('/direccions/update', [DireccionController::class, 'update']);
Route::delete('/direccions/delete', [DireccionController::class, 'delete']);

