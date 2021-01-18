<?php

use App\Http\Controllers\ListController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsAndListsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function(){
    Route::get('/user', [UserController::class])->name('user');

    Route::name('lists.')->group(function() {
        Route::get('/lists', [ListController::class, 'getLists'])->name('getLists');
        Route::post('/lists', [ListController::class, 'createList'])->name('createList');
        Route::get('/list/{id}', [ListController::class, 'listDetails'])->name('listDetails');
        Route::put('/list/{id}', [ListController::class, 'editList'])->name('editList');
        Route::delete('/list/{id}', [ListController::class, 'deleteList'])->name('deleteList');
    });

    Route::name('products.')->group(function() {
        Route::get('/products', [ProductController::class, 'getProducts'])->name('getProducts');
        Route::get('/product/{id}', [ProductController::class, 'getDetails'])->name('productDetails');
    });

    Route::name('list_products.')->group(function() {
        Route::get('/list/{listId}/product/{productId}', [ProductsAndListsController::class, 'getDetails'])
            ->name('getDetails');
        Route::put('/list/{listId}/product/{productId}', [ProductsAndListsController::class, 'addProductToList'])
            ->name('addProductToList');
        Route::post('/list/{listId}/product/{productId}', [ProductsAndListsController::class, 'editProductOnList'])
            ->name('editProductOnList');
        Route::delete('/list/{listId}/product/{productId}', [ProductsAndListsController::class, 'removeProductFromList'])
            ->name('removeProductFromList');
    });
});

Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
