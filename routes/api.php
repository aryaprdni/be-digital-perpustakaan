<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/users', [UserController::class, 'register']);
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(ApiAuthMiddleware::class)->group(function () {
    Route::get('/users/current', [UserController::class, 'get']);
    Route::delete('/users/logout', [UserController::class, 'logout']);

    Route::post('/book', [BookController::class, 'create']);
    Route::put('/book/{id}', [BookController::class, 'update']);
    Route::delete('/book/{id}', [BookController::class, 'delete']);

    Route::get('/books', [BookController::class, 'getAllBook']);

    Route::get('/bookbyid/{id}', [BookController::class, 'getBookById']);

    Route::get('/book/{category_id}', [BookController::class, 'GetAllBookByFilterByCategory']);

    Route::post('/category', [CategoryController::class, 'create']);
    Route::put('/category/{id}', [CategoryController::class, 'update']);
    Route::get('/category', [CategoryController::class, 'getAll']);
    Route::get('/category/{id}', [CategoryController::class, 'get']);
    Route::delete('/category/{id}', [CategoryController::class, 'delete']);
});
