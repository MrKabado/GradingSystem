<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
  Route::get('/login', function () {
    return view('auth.login');
  })->name('login');

  Route::post('/login', [AuthController::class, 'login']);
});

  Route::get('/dashboard', function () {
    return view('dashboard.index');
  })->middleware('auth')->name('dashboard');

  Route::get('/students', function () {
    return view('students.index');
  })->middleware('auth')->name('students.index');

  Route::get('/subjects', function () {
    return view('subjects.index');
  })->middleware('auth')->name('subjects.index');

  Route::get('/grades', function () {
    return view('grades.index');
  })->middleware('auth')->name('grades.index');