<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatoProductoController;
use App\Http\Controllers\NombreProductoController;
use App\Http\Controllers\DatoClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\DetalleFacturaController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\TelefonoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\CiudadController;

// 🔐 Autenticación
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 👤 Usuarios
Route::post('/users/register', [UserController::class, 'register']);
Route::put('/users/update/{id}', [UserController::class, 'update']);
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/usuarios/{id}', [UserController::class, 'show']);
Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);

// 📦 Productos
Route::get('/dato_productos/getdata', [DatoProductoController::class, 'getdata']);
Route::post('/dato_productos/save', [DatoProductoController::class, 'save']);
Route::put('/dato_productos/update', [DatoProductoController::class, 'update']);
Route::delete('/dato_productos/delete', [DatoProductoController::class, 'delete']);

// Nombre del producto
Route::get('/nombre_productos/getdata', [NombreProductoController::class, 'getData']);
Route::get('/nombre_productos/getdataById/{id}', [NombreProductoController::class, 'getDataById']);
Route::post('/nombre_productos/save', [NombreProductoController::class, 'save']);
Route::put('/nombre_productos/update/{id}', [NombreProductoController::class, 'update']);
Route::delete('/nombre_productos/delete/{id}', [NombreProductoController::class, 'delete']);

// 👥 Clientes
Route::get('/dato_clientes/getdata', [DatoClienteController::class, 'getdata']);
Route::get('/dato_clientes/getdataById/{id}', [DatoClienteController::class, 'getDataById']);
Route::post('/dato_clientes/save', [DatoClienteController::class, 'save']);
Route::put('/dato_clientes/update/{id}', [DatoClienteController::class, 'update']);
Route::delete('/dato_clientes/delete/{id}', [DatoClienteController::class, 'delete']);

// 📄 Facturas (rutas modernas)
Route::post('/facturas', [FacturaController::class, 'store']);
Route::get('/facturas/nextNumero', [FacturaController::class, 'getNextNumeroFactura']);
Route::get('/facturas', [FacturaController::class, 'getFacturas']);
Route::put('/facturas/update', [FacturaController::class, 'update']);
Route::delete('/facturas/delete', [FacturaController::class, 'delete']);
Route::get('/facturas/byId/{id}', [FacturaController::class, 'byId']);

// Detalle de factura
//Route::get('/detalle_facturas/byFactura/{factura_id}', [DetalleFacturaController::class, 'getByFactura']);

// 📓 Asientos contables
Route::get('/asientos', [AsientoController::class, 'index']);
Route::post('/asientos', [AsientoController::class, 'save']);
Route::get('/asientos/ultimo-consecutivo', [AsientoController::class, 'ultimoConsecutivo']);

// 💼 Cuentas contables
Route::apiResource('cuentas', CuentaController::class);
Route::get('/cuentas-contables', [CuentaController::class, 'index']);

// 📞 Teléfonos
Route::get('/telefonos/getdata', [TelefonoController::class, 'getdata']);
Route::post('/telefonos/save', [TelefonoController::class, 'save']);
Route::put('/telefonos/update', [TelefonoController::class, 'update']);
Route::delete('/telefonos/delete', [TelefonoController::class, 'delete']);

// 📧 Correos
Route::get('/correos/getdata', [CorreoController::class, 'getdata']);
Route::post('/correos/save', [CorreoController::class, 'save']);
Route::put('/correos/update', [CorreoController::class, 'update']);
Route::delete('/correos/delete', [CorreoController::class, 'delete']);

// 🌆 Ciudades
Route::get('/ciudads/getdata', [CiudadController::class, 'getdata']);
Route::post('/ciudads/save', [CiudadController::class, 'save']);
Route::put('/ciudads/update', [CiudadController::class, 'update']);
Route::delete('/ciudads/delete', [CiudadController::class, 'delete']);

Route::get('/roles', [UserController::class, 'roles']);
