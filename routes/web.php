<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GradeReportController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentReportController;
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

    Route::post('/logout', [AuthController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::resource('sections', SectionController::class)->except(['show']);
    Route::resource('subjects', SubjectController::class)->except(['show']);

    Route::resource('grades', GradeController::class)->parameters([
        'grades' => 'student',
    ]);

    Route::get('/grade-report', [GradeReportController::class, 'index'])->name('grade-reports.index');
    Route::get('/grade-reports/{student}/pdf', [StudentReportController::class, 'pdfDownload'])->name('grade-reports.pdf');
    Route::post('/grade-reports/{student}/approve', [StudentReportController::class, 'approve'])->name('grade-reports.approve');
});
