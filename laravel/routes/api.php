<?php

use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/produtos', [ProductController::class, 'list']);
Route::get('/produtos/trashed', [ProductController::class, 'lixeira']);
Route::delete('/produtos/{produto}', [ProductController::class, 'destroy']);
