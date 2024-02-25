<?php

use App\Http\Controllers\BatchZaikoNoticeController;
use App\Http\Controllers\ItemListController;
use App\Http\Controllers\TodoListController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {return view('welcome');});
Route::get('/list', [TodoListController::class, 'index']);
Route::get('/item_list', [ItemListController::class, 'index']);
Route::post('/item_search', [ItemListController::class, 'search']);
Route::post('/item_list', [ItemListController::class, 'index']);
Route::get('/item_input', [ItemListController::class, 'insert']);
Route::post('/item_input', [ItemListController::class, 'insert']);
// Route::get('/item_del', [ItemListController::class, 'delete']);
Route::post('/item_del', [ItemListController::class, 'delete']);
// Route::get('/item_edit', [ItemListController::class, 'update']);
Route::post('/item_edit', [ItemListController::class, 'update']);
Route::get('/zaiko_notice', [BatchZaikoNoticeController::class, 'notice']);
