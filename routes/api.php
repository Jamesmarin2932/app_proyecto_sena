<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturaController;

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