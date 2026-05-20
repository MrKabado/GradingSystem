<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.header', function ($view) {
            $view->with('pageTitle', $this->resolvePageTitle());
        });
    }

    private function resolvePageTitle(): string
    {
        return match (true) {
            request()->routeIs('dashboard') => 'Overview',
            request()->routeIs('students.create') => 'Add Student',
            request()->routeIs('students.edit') => 'Edit Student',
            request()->routeIs('students.show') => 'Student Profile',
            request()->routeIs('students.*') => 'Students',
            request()->routeIs('sections.create') => 'Add Section',
            request()->routeIs('sections.edit') => 'Edit Section',
            request()->routeIs('sections.*') => 'Sections',
            request()->routeIs('subjects.create') => 'Add Subject',
            request()->routeIs('subjects.edit') => 'Edit Subject',
            request()->routeIs('subjects.*') => 'Subjects',
            request()->routeIs('grades.show') => 'View Grades',
            request()->routeIs('grades.edit') => 'Edit Grades',
            request()->routeIs('grades.create') => 'Add Grades',
            request()->routeIs('grades.*') => 'Grades',
            request()->routeIs('grade-reports.*') => 'Grade Reports',
            default => 'GradeSync',
        };
    }
}
