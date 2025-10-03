<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DatoProductoController;
use App\Http\Controllers\NombreProductoController;
use App\Http\Controllers\DatoClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\AsientoController;
use App\Http\Controllers\CuentaController;
use App\Http\Controllers\TelefonoController;
use App\Http\Controllers\CorreoController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CuentaEmpresaController;

/*
|--------------------------------------------------------------------------
| 🔐 Autenticación (Públicas)
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| 👤 Usuario Autenticado
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| 🏢 Gestión de Empresa (sin middleware empresa.activa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    // Selección de empresa
    Route::post('/asientos/establecer-empresa', [AsientoController::class, 'establecerEmpresaActual']);
    Route::get('/asientos/empresa-actual', [AsientoController::class, 'getEmpresaActual']);
    
    // Empresas del usuario
    Route::apiResource('empresas', EmpresaController::class);
    Route::get('/mis-empresas', [EmpresaController::class, 'misEmpresas']);
});

/*
|--------------------------------------------------------------------------
| 📊 Datos Maestros (requieren empresa activa)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'empresa.activa'])->group(function () {
    
    // 👥 CLIENTES
    Route::prefix('dato_clientes')->group(function () {
        Route::get('/getdata', [DatoClienteController::class, 'getData']);
        Route::get('/getdataById/{id}', [DatoClienteController::class, 'getDataById']);
        Route::post('/save', [DatoClienteController::class, 'save']);
        Route::put('/update/{id}', [DatoClienteController::class, 'update']);
        Route::delete('/delete/{id}', [DatoClienteController::class, 'delete']);
    });

    // 📦 PRODUCTOS
    Route::prefix('dato_productos')->group(function () {
        Route::get('/getdata', [DatoProductoController::class, 'getdata']);
        Route::post('/save', [DatoProductoController::class, 'save']);
        Route::put('/update', [DatoProductoController::class, 'update']);
        Route::delete('/delete', [DatoProductoController::class, 'delete']);
    });

    Route::prefix('nombre_productos')->group(function () {
        Route::get('/getdata', [NombreProductoController::class, 'getData']);
        Route::get('/getdataById/{id}', [NombreProductoController::class, 'getDataById']);
        Route::post('/save', [NombreProductoController::class, 'save']);
        Route::put('/update/{id}', [NombreProductoController::class, 'update']);
        Route::delete('/delete/{id}', [NombreProductoController::class, 'delete']);
    });

    // 📄 FACTURAS
    Route::prefix('facturas')->group(function () {
        Route::post('/', [FacturaController::class, 'store']);
        Route::get('/nextNumero', [FacturaController::class, 'getNextNumeroFactura']);
        Route::get('/', [FacturaController::class, 'getFacturas']);
        Route::get('/byId/{id}', [FacturaController::class, 'byId']);
        Route::put('/update', [FacturaController::class, 'update']);
        Route::delete('/delete', [FacturaController::class, 'delete']);
    });

   // 📓 ASIENTOS CONTABLES - RUTAS CORREGIDAS
Route::prefix('asientos')->group(function () {

    // 👉 NUEVA: Eliminar asientos por tipo + consecutivo
    Route::delete('/{tipo}/{consecutivo}', [AsientoController::class, 'deleteByTipoConsecutivo'])
        ->where([
            'tipo' => '[A-Z]+',
            'consecutivo' => '[0-9]+'
        ]);

    // 👉 Obtener asientos por tipo + consecutivo
    Route::get('/{tipo}/{consecutivo}', [AsientoController::class, 'getByConsecutivoTipo'])
        ->where([
            'tipo' => '[A-Z]+',
            'consecutivo' => '[0-9]+'
        ]);

    // CRUD básico
    Route::get('/', [AsientoController::class, 'index']);              // Listar todos los asientos
    Route::post('/save', [AsientoController::class, 'save']);          // Crear nuevo asiento
    Route::get('/{id}', [AsientoController::class, 'show']);           // Obtener asiento por ID
    Route::put('/{id}', [AsientoController::class, 'update']);         // Actualizar asiento individual
    Route::delete('/{id}', [AsientoController::class, 'destroy']);     // Eliminar asiento individual

    // Consecutivo
    Route::get('/ultimo-consecutivo/{tipo}', [AsientoController::class, 'ultimoConsecutivo'])
        ->where('tipo', '[A-Z]+');

    // Operaciones por consecutivo
    Route::get('/consecutivo/{consecutivo}', [AsientoController::class, 'getByConsecutivo']);         // Obtener asientos por consecutivo
    Route::put('/consecutivo/{consecutivo}', [AsientoController::class, 'updateByConsecutivo']);      // Actualizar todos los asientos de un consecutivo
    Route::delete('/consecutivo/{consecutivo}', [AsientoController::class, 'deleteByConsecutivo']);   // Eliminar todos los asientos de un consecutivo
});


    // 💼 CUENTAS CONTABLES
    Route::prefix('cuentas')->group(function () {
        // Cuentas globales (PUC)
        Route::get('/contables', [CuentaController::class, 'index']);
        Route::apiResource('', CuentaController::class)->except(['index']);
    });

    // 📞 TELÉFONOS
    Route::prefix('telefonos')->group(function () {
        Route::get('/getdata', [TelefonoController::class, 'getdata']);
        Route::post('/save', [TelefonoController::class, 'save']);
        Route::put('/update', [TelefonoController::class, 'update']);
        Route::delete('/delete', [TelefonoController::class, 'delete']);
    });

    // 📧 CORREOS
    Route::prefix('correos')->group(function () {
        Route::get('/getdata', [CorreoController::class, 'getdata']);
        Route::post('/save', [CorreoController::class, 'save']);
        Route::put('/update', [CorreoController::class, 'update']);
        Route::delete('/delete', [CorreoController::class, 'delete']);
    });

    // 🌆 CIUDADES
    Route::prefix('ciudades')->group(function () {
        Route::get('/getdata', [CiudadController::class, 'getdata']);
        Route::post('/save', [CiudadController::class, 'save']);
        Route::put('/update', [CiudadController::class, 'update']);
        Route::delete('/delete', [CiudadController::class, 'delete']);
    });
});

/*
|--------------------------------------------------------------------------
| 💼 CUENTAS POR EMPRESA
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'empresa.activa'])->group(function () {
    Route::prefix('empresas/{empresaId}')->group(function () {
        Route::prefix('cuentas-empresa')->group(function () {
            Route::get('/', [CuentaEmpresaController::class, 'index']);
            Route::post('/', [CuentaEmpresaController::class, 'store']);
            Route::get('/{id}', [CuentaEmpresaController::class, 'show']);
            Route::put('/{id}', [CuentaEmpresaController::class, 'update']);
            Route::delete('/{id}', [CuentaEmpresaController::class, 'destroy']);
        });

        // 🚀 Ruta combinada: Globales + Empresa
        Route::get('/cuentas-todas', [CuentaEmpresaController::class, 'todas']);
    });
});

/*
|--------------------------------------------------------------------------
| 👤 ADMINISTRACIÓN DE USUARIOS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/users/register', [UserController::class, 'register'])
        ->withoutMiddleware(['empresa.activa']); 
    Route::put('/users/update/{id}', [UserController::class, 'update']);
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| 📌 ROLES (Público o según necesidad)
|--------------------------------------------------------------------------
*/
Route::get('/roles', [UserController::class, 'roles']);