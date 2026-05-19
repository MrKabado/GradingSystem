<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Total Students
        $totalStudents = Student::count();

        // 2. New Students registered this month
        $newStudentsThisMonth = Student::where('created_at', '>=', now()->startOfMonth())->count();

        // 3. Grade Levels count & range
        $gradeLevels = Section::distinct()->pluck('year_level')->sort()->values();
        $totalGradeLevels = $gradeLevels->count();

        if ($totalGradeLevels > 0) {
            $minGrade = $gradeLevels->first();
            $maxGrade = $gradeLevels->last();
            if ($minGrade == $maxGrade) {
                $gradeLevelsRange = "Grade " . $minGrade;
            } else {
                $gradeLevelsRange = "Grades " . $minGrade . " - " . $maxGrade;
            }
        } else {
            $gradeLevelsRange = "No Grades";
        }

        // 4. Sections count & names list
        $totalSections = Section::count();
        $sectionNamesList = Section::distinct()->pluck('section')->sort()->implode(', ');
        if (empty($sectionNamesList)) {
            $sectionNamesList = "None";
        }

        // 5. Recent Activity
        $recentActivities = ActivityLog::latest()->take(5)->get();

        return view('dashboard.index', compact(
            'totalStudents',
            'newStudentsThisMonth',
            'totalGradeLevels',
            'gradeLevelsRange',
            'totalSections',
            'sectionNamesList',
            'recentActivities'
        ));
    }
}
