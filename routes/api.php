<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BomController;

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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('products',  ProductController::class)
    ->middleware('auth:sanctum');
    
Route::apiResource('categories',  CategoryController::class)
    ->middleware('auth:sanctum');

Route::apiResource('tags',  TagController::class)
    ->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('products/{product}/bom', [BomController::class, 'show']);
    Route::post('products/{product}/bom', [BomController::class, 'store']);
    Route::put('products/{product}/bom/{bom}', [BomController::class, 'update']);
    Route::delete('products/{product}/bom/{bom}', [BomController::class, 'destroy']);
});