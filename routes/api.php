<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {

    Route::apiResource('tasks', TaskController::class);

    Route::get('tasksbystatus', [TaskController::class, 'getTasksByStatus']);

    Route::get('search', [TaskController::class, 'search']);

    Route::get('sorting', [TaskController::class, 'sorting']);
});


Route::post('register', [UserController::class, 'register']);

Route::post('login', [UserController::class, 'login']);

Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');
