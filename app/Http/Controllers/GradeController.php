<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        return view('grades.index', $this->indexPayload($request));
    }

    public function create(Request $request): View
    {
        return view('grades.index', array_merge($this->indexPayload($request), [
            'modalMode' => 'create',
            'gradeFormStudent' => new Student,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'grades' => ['required', 'array'],
            'grades.*' => ['array'],
            'grades.*.Q1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q4' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $studentId = $validated['student_id'];
        $gradesData = $validated['grades'];

        foreach ($gradesData as $subjectId => $quarters) {
            foreach (Grade::QUARTERS as $quarter) {
                $score = $quarters[$quarter] ?? null;
                $score = ($score === null || $score === '') ? null : (float) $score;

                if ($score !== null) {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $studentId,
                            'subject_id' => $subjectId,
                            'quarter' => $quarter,
                        ],
                        [
                            'grade' => $score,
                            'is_final' => false,
                        ]
                    );
                } else {
                    Grade::where('student_id', $studentId)
                        ->where('subject_id', $subjectId)
                        ->where('quarter', $quarter)
                        ->delete();
                }
            }
        }

        $student = Student::find($studentId);
        ActivityLog::record(
            'created',
            'grades',
            $student->id,
            'Recorded grades for ' . $student->full_name
        );

        $section = Section::find($student->section_id);

        return redirect()
            ->route('grades.index', [
                'year_level' => $section?->year_level,
                'section' => $section?->section,
            ])
            ->with('status', 'Grades saved successfully.');
    }

    public function show(Request $request, Student $student): View
    {
        return view('grades.index', $this->indexPayload($request));
    }

    public function edit(Request $request, Student $student): View
    {
        return view('grades.index', $this->indexPayload($request));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'grades' => ['required', 'array'],
            'grades.*' => ['array'],
            'grades.*.Q1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'grades.*.Q4' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $gradesData = $validated['grades'];

        foreach ($gradesData as $subjectId => $quarters) {
            foreach (Grade::QUARTERS as $quarter) {
                $score = $quarters[$quarter] ?? null;
                $score = ($score === null || $score === '') ? null : (float) $score;

                if ($score !== null) {
                    Grade::updateOrCreate(
                        [
                            'student_id' => $student->id,
                            'subject_id' => $subjectId,
                            'quarter' => $quarter,
                        ],
                        [
                            'grade' => $score,
                            'is_final' => false,
                        ]
                    );
                } else {
                    Grade::where('student_id', $student->id)
                        ->where('subject_id', $subjectId)
                        ->where('quarter', $quarter)
                        ->delete();
                }
            }
        }

        ActivityLog::record(
            'updated',
            'grades',
            $student->id,
            'Updated grades for ' . $student->full_name
        );

        $section = $student->section;

        return redirect()
            ->route('grades.index', [
                'year_level' => $section?->year_level,
                'section' => $section?->section,
            ])
            ->with('status', 'Grades updated successfully.');
    }

    public function destroy(Request $request, Student $student): RedirectResponse
    {
        $subjects = Subject::where('section_id', $student->section_id)->pluck('id');

        Grade::query()
            ->where('student_id', $student->id)
            ->whereIn('subject_id', $subjects)
            ->delete();

        ActivityLog::record('deleted', 'grades', $student->id, 'Deleted grades for ' . $student->full_name);

        $section = $student->section;

        return redirect()
            ->route('grades.index', [
                'year_level' => $section?->year_level,
                'section' => $section?->section,
            ])
            ->with('status', 'Grades removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexPayload(Request $request): array
    {
        $sections = Section::orderBy('year_level')
            ->orderBy('section')
            ->get();

        $yearLevels = $sections->pluck('year_level')->unique()->sort()->values();
        $sectionNames = $sections->pluck('section')->unique()->sort()->values();

        $selectedYearLevel = $request->input('year_level', $yearLevels->first());
        $selectedSectionName = $request->input('section', $sectionNames->first());
        $search = $request->input('search');

        $activeSection = Section::where('year_level', $selectedYearLevel)
            ->where('section', $selectedSectionName)
            ->first();

        $gradeRows = [];
        $stats = [
            'total' => 0,
            'passed' => 0,
            'failed' => 0,
            'average' => null,
        ];
        $students = collect();
        $subjects = collect();

        if ($activeSection) {
            $subjects = Subject::where('section_id', $activeSection->id)
                ->with('teacher')
                ->orderBy('name')
                ->get();

            $studentsQuery = Student::where('section_id', $activeSection->id);
            if ($search) {
                $studentsQuery->where(function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('student_id', 'like', "%{$search}%");
                });
            }
            $students = $studentsQuery->orderBy('last_name')->orderBy('first_name')->get();

            if ($subjects->isNotEmpty()) {
                $studentIds = $students->pluck('id');
                $sectionSubjectIds = $subjects->pluck('id');

                $grades = Grade::whereIn('student_id', $studentIds)
                    ->whereIn('subject_id', $sectionSubjectIds)
                    ->get()
                    ->groupBy('student_id');

                foreach ($students as $student) {
                    $studentGrades = $grades->get($student->id, collect());

                    $quarterScores = [
                        'Q1' => [],
                        'Q2' => [],
                        'Q3' => [],
                        'Q4' => [],
                    ];

                    $subjectAverages = [];

                    foreach ($subjects as $sub) {
                        $subGrades = $studentGrades->where('subject_id', $sub->id);

                        $subQuarterValues = [];
                        foreach (Grade::QUARTERS as $quarter) {
                            $record = $subGrades->firstWhere('quarter', $quarter);
                            if ($record && $record->grade !== null) {
                                $quarterScores[$quarter][] = (float)$record->grade;
                                $subQuarterValues[] = (float)$record->grade;
                            }
                        }
                        if ($subQuarterValues !== []) {
                            $subjectAverages[] = array_sum($subQuarterValues) / count($subQuarterValues);
                        }
                    }

                    $qAverages = [];
                    foreach (Grade::QUARTERS as $quarter) {
                        $scores = $quarterScores[$quarter];
                        $qAverages[$quarter] = $scores === [] ? null : round(array_sum($scores) / count($scores), 0);
                    }

                    $gpa = $subjectAverages === [] ? null : round(array_sum($subjectAverages) / count($subjectAverages), 0);
                    $remarks = $gpa === null ? null : ($gpa >= Grade::PASSING_SCORE ? 'Passed' : 'Failed');

                    $gradeRows[] = [
                        'student_id' => $student->id,
                        'student_no' => $student->student_id,
                        'name' => $student->full_name,
                        'grades' => $qAverages,
                        'average' => $gpa,
                        'remarks' => $remarks,
                    ];
                }

                $withAverage = array_filter($gradeRows, fn($row) => $row['average'] !== null);
                $passedCount = count(array_filter($withAverage, fn($row) => $row['remarks'] === 'Passed'));
                $failedCount = count(array_filter($withAverage, fn($row) => $row['remarks'] === 'Failed'));
                $averages = array_column($withAverage, 'average');
                $classAverage = $averages === [] ? null : round(array_sum($averages) / count($averages), 1);

                $stats = [
                    'total' => count($gradeRows),
                    'passed' => $passedCount,
                    'failed' => $failedCount,
                    'average' => $classAverage,
                ];
            }
        }

        $modalMode = $request->input('modal');
        $gradeFormStudent = null;
        $reportCardRows = [];
        $gpa = null;
        $gpaRemarks = null;

        $modalMode = $modalMode ?: ($request->route() ? $request->route()->action['as'] : null);
        if ($modalMode) {
            if (str_contains($modalMode, 'show')) {
                $modalMode = 'view';
            } elseif (str_contains($modalMode, 'edit')) {
                $modalMode = 'edit';
            }
        }

        $studentParam = $request->route('student') ?: $request->input('student_id');
        if (in_array($modalMode, ['view', 'edit']) && $studentParam) {
            $gradeFormStudent = $studentParam instanceof Student
                ? $studentParam->load('section')
                : Student::with('section')->find($studentParam);
            if ($gradeFormStudent) {
                $sectionSubjects = Subject::where('section_id', $gradeFormStudent->section_id)
                    ->with('teacher')
                    ->orderBy('name')
                    ->get();

                $studentAllGrades = Grade::where('student_id', $gradeFormStudent->id)
                    ->get()
                    ->groupBy('subject_id');

                $totalFinalGrades = [];
                foreach ($sectionSubjects as $sub) {
                    $subGrades = $studentAllGrades->get($sub->id, collect());
                    $qGrades = [];
                    foreach (Grade::QUARTERS as $quarter) {
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

                $gpa = $totalFinalGrades === [] ? null : round(array_sum($totalFinalGrades) / count($totalFinalGrades), 1);
                $gpaRemarks = $gpa === null ? null : ($gpa >= Grade::PASSING_SCORE ? 'Passed' : 'Failed');
            }
        }

        return [
            'sections' => $sections,
            'yearLevels' => $yearLevels,
            'sectionNames' => $sectionNames,
            'selectedYearLevel' => $selectedYearLevel,
            'selectedSection' => $selectedSectionName,
            'activeSection' => $activeSection,
            'subjects' => $subjects,
            'gradeRows' => $gradeRows,
            'stats' => $stats,
            'quarters' => Grade::QUARTERS,
            'students' => $students,
            
            'modalMode' => $modalMode,
            'gradeFormStudent' => $gradeFormStudent,
            'reportCardRows' => $reportCardRows,
            'gpa' => $gpa,
            'gpaRemarks' => $gpaRemarks,
        ];
    }
}
