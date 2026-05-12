<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('sections', SectionController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);

    Route::get('/grades', function () {
        return view('grades.index');
    })->name('grades.index');

    Route::get('/grade-report', function () {
        return view('grade-reports.index');
    })->name('grade-reports.index');
});
