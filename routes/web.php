<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
  Route::get('/login', function () {
    return view('auth.login');
  });

  Route::post('/login', [AuthController::class, 'login']);
});

  Route::get('/dashboard', function () {
    return view('dashboard.index');
  });
