<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskController;

Route::get('/users', [UserController::class, 'index']);
Route::get('/tasks', [TaskController::class, 'index']);
