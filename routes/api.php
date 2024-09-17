<?php

use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('tasks', [TasksController::class, 'index']);
Route::post('tasks', [TasksController::class, 'store']);
Route::get('tasks/{id}', [TasksController::class, 'show']);
Route::put('tasks', [TasksController::class, 'update']);
Route::delete('tasks/{id}', [TasksController::class, 'destroy']);

Route::get('projects', [ProjectController::class, 'index']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
