<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\TransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:api');
route::middleware('auth:api')->group(function () {

route::apiResource('/transaction', TransactionController::class)->only('index','show','store');
route::middleware('role:admin')->group(function () {
    Route::apiResource('/books', BookController::class)->only('store','update','destroy');
    Route::apiResource('/author', AuthorController::class)->only('store','update','destroy');
    Route::apiResource('/genre', GenreController::class)->only('store','update','destroy');
    route::apiResource('/transaction', TransactionController::class)->only('index','show','destroy');
    });
    route::middleware('role:customer')->group(function () {
    route::apiResource('/transaction', TransactionController::class)->only('store','update','show');
    });});
Route::apiResource('/books', BookController::class)->only('index','show');
Route::apiResource('/author', AuthorController::class)->only('index','show');
Route::apiResource('/genre',GenreController::class)->only('index','show');