<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lists', [ListController::class, 'getLists'])->name('login');
Route::post('/lists', [ListController::class, 'createList'])->name('login');
Route::get('/list/{id}', [ListController::class, 'listDetails'])->name('login');
Route::post('/list/{id}', [ListController::class, 'editList'])->name('login');
Route::delete('/list/{id}', [ListController::class, 'deleteList'])->name('login');
