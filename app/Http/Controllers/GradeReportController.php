<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\GradeReport;
use Illuminate\View\View;

class GradeReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        // 1. Fetch sections & filter options
        $sectionsList = Section::orderBy('year_level')->orderBy('section')->get();
        $yearLevels = $sectionsList->pluck('year_level')->unique()->sort()->values();
        $sectionNames = $sectionsList->pluck('section')->unique()->sort()->values();

        $selectedYearLevel = $request->input('year_level');
        $selectedSection = $request->input('section');
        $search = $request->input('search');

        // 2. Fetch and filter students
        $studentsQuery = Student::with(['section', 'grades']);

        if ($selectedYearLevel) {
            $studentsQuery->whereHas('section', function ($q) use ($selectedYearLevel) {
                $q->where('year_level', $selectedYearLevel);
            });
        }

        if ($selectedSection) {
            $studentsQuery->whereHas('section', function ($q) use ($selectedSection) {
                $q->where('section', $selectedSection);
            });
        }

        if ($search) {
            $studentsQuery->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $students = $studentsQuery->orderBy('last_name')->orderBy('first_name')->get();

        // 3. Load all grade reports for the filtered students
        $reports = GradeReport::whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');

        // 4. Compute student averages (GPA) and build items list
        $items = [];
        $availableCount = 0;

        foreach ($students as $student) {
            $gpa = null;
            if ($student->section_id) {
                $subjects = Subject::where('section_id', $student->section_id)->get();
                $studentGrades = $student->grades;

                $subjectAverages = [];
                foreach ($subjects as $sub) {
                    $subGrades = $studentGrades->where('subject_id', $sub->id);
                    $scores = [];
                    foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter) {
                        $rec = $subGrades->firstWhere('quarter', $quarter);
                        if ($rec && $rec->grade !== null) {
                            $scores[] = (float) $rec->grade;
                        }
                    }
                    if ($scores !== []) {
                        $subjectAverages[] = array_sum($scores) / count($scores);
                    }
                }
                if ($subjectAverages !== []) {
                    $gpa = round(array_sum($subjectAverages) / count($subjectAverages), 1);
                }
            }

            $report = $reports->get($student->id);
            $isAvailable = $report !== null;
            if ($isAvailable) {
                $availableCount++;
            }

            $items[] = [
                'id' => $student->id,
                'student_id' => $student->student_id,
                'name' => $student->full_name,
                'section' => $student->section ? $student->section->display_name : '—',
                'average' => $gpa !== null ? $gpa . '%' : '—',
                'status' => $isAvailable ? 'Available' : 'Pending',
                'date-generated' => $isAvailable ? $report->created_at->format('M d, Y') : '—'
            ];
        }

        // 5. Statistics counts
        $totalReports = count($items);
        $pendingCount = $totalReports - $availableCount;

        $cards = [
            ['name' => 'Total Reports', 'value' => $totalReports],
            ['name' => 'Available', 'value' => $availableCount],
            ['name' => 'Pending', 'value' => $pendingCount],
        ];

        // 6. View Modal details (if a student is being previewed)
        $viewStudent = null;
        $reportCardRows = [];
        $viewGpa = null;
        $viewGpaRemarks = null;
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];

        $viewStudentId = $request->input('view_student');
        if ($viewStudentId) {
            $viewStudent = Student::with('section')->find($viewStudentId);
            if ($viewStudent) {
                $sectionSubjects = Subject::where('section_id', $viewStudent->section_id)
                    ->with('teacher')
                    ->orderBy('name')
                    ->get();

                $studentAllGrades = Grade::where('student_id', $viewStudent->id)
                    ->get()
                    ->groupBy('subject_id');

                $totalFinalGrades = [];
                foreach ($sectionSubjects as $sub) {
                    $subGrades = $studentAllGrades->get($sub->id, collect());
                    $qGrades = [];
                    foreach ($quarters as $quarter) {
                        $rec = $subGrades->firstWhere('quarter', $quarter);
                        $qGrades[$quarter] = $rec?->grade !== null ? (float) $rec->grade : null;
                    }
                    $filledQ = array_filter($qGrades, fn($v) => $v !== null);
                    $subAvg = $filledQ === [] ? null : round(array_sum($filledQ) / count($filledQ), 1);
                    if ($subAvg !== null) {
                        $totalFinalGrades[] = $subAvg;
                    }
                    $reportCardRows[] = [
                        'subject_id' => $sub->id,
                        'subject' => $sub->name,
                        'teacher' => $sub->teacher?->name ?? '—',
                        'grades' => $qGrades,
                        'average' => $subAvg,
                        'remarks' => $subAvg === null ? '—' : ($subAvg >= Grade::PASSING_SCORE ? 'Passed' : 'Failed'),
                    ];
                }

                $viewGpa = $totalFinalGrades === [] ? null : round(array_sum($totalFinalGrades) / count($totalFinalGrades), 1);
                $viewGpaRemarks = $viewGpa === null ? null : ($viewGpa >= Grade::PASSING_SCORE ? 'Passed' : 'Failed');
            }
        }

        return view('grade-reports.index', compact(
            'items',
            'cards',
            'yearLevels',
            'sectionNames',
            'selectedYearLevel',
            'selectedSection',
            'search',
            'viewStudent',
            'reportCardRows',
            'viewGpa',
            'viewGpaRemarks',
            'quarters'
        ));
    }
}
