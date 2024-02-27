<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
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

Route::get('/', [HomeController::class, 'show'])
    ->name(('welcome'));

Route::prefix('products/category')->group(function () {
    Route::get('{category_slug}/', [CategoryController::class, 'show'])
        ->name('categories.show');
    Route::get('{category_slug}/{product_slug}', [ProductController::class, 'show'])
        ->name('products.showByCategory');
});

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product_slug}/download-files', [ProductController::class, 'downloadFiles'])
    ->name('products.downloadFiles');

Route::prefix('products')->group(function () {
    Route::get('categories', [ProductController::class, 'index'])
        ->name('products.index');
    Route::get('/', [ProductController::class, 'index'])
        ->name('products.index');
});

Route::prefix('products')->group(function () {
    Route::get('tags', [TagController::class, 'index'])
        ->name('tags.index');
    Route::get('tag/{tag_slug}', [TagController::class, 'show'])
        ->name('tags.show');
    Route::get('{tag_slug}/{product_slug}', [ProductController::class, 'show'])
        ->name('products.showByTag');
});

