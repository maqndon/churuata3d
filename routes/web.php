<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'show'])
    ->name(('welcome'));

Route::prefix('products')->group(function () {
    Route::get('{category_slug}/', [ProductController::class, 'indexCategory'])
        ->name('products.indexCategory');
    Route::get('{category_slug}/{product_slug}', [ProductController::class, 'show'])
        ->name('products.show');
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{category_slug}/{product_slug}/download-files', [ProductController::class, 'downloadFiles']);
