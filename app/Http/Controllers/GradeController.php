<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
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
        $validated = $this->validatedGradeData($request);
        $this->syncQuarterGrades($validated);

        ActivityLog::record(
            'created',
            'grades',
            $validated['student_id'],
            'Recorded grades for student #' . $validated['student_id']
        );

        return redirect()
            ->route('grades.index', ['subject_id' => $validated['subject_id']])
            ->with('status', 'Grades saved successfully.');
    }

    public function show(Request $request, Student $student): View
    {
        $subjectId = $this->resolveSubjectId($request);

        return view('grades.index', array_merge($this->indexPayload($request), [
            'modalMode' => 'view',
            'gradeFormStudent' => $student->load('section'),
            'gradeFormQuarters' => $this->quarterGradesFor($student->id, $subjectId),
            'selectedSubjectId' => $subjectId,
        ]));
    }

    public function edit(Request $request, Student $student): View
    {
        $subjectId = $this->resolveSubjectId($request);

        return view('grades.index', array_merge($this->indexPayload($request), [
            'modalMode' => 'edit',
            'gradeFormStudent' => $student->load('section'),
            'gradeFormQuarters' => $this->quarterGradesFor($student->id, $subjectId),
            'selectedSubjectId' => $subjectId,
        ]));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $this->validatedGradeData($request);
        $this->syncQuarterGrades($validated);

        ActivityLog::record(
            'updated',
            'grades',
            $student->id,
            'Updated grades for ' . $student->full_name
        );

        return redirect()
            ->route('grades.index', ['subject_id' => $validated['subject_id']])
            ->with('status', 'Grades updated successfully.');
    }

    public function destroy(Request $request, Student $student): RedirectResponse
    {
        $validated = $request->validate([
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $name = $student->full_name;
        $subjectId = (int) $validated['subject_id'];

        Grade::query()
            ->where('student_id', $student->id)
            ->where('subject_id', $subjectId)
            ->delete();

        ActivityLog::record('deleted', 'grades', $student->id, 'Deleted grades for ' . $name);

        return redirect()
            ->route('grades.index', ['subject_id' => $subjectId])
            ->with('status', 'Grades removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexPayload(Request $request): array
    {
        $subjects = Subject::orderBy('name')->get();
        $selectedSubjectId = $this->resolveSubjectId($request, $subjects);
        $gradeRows = $this->buildGradeRows($selectedSubjectId);
        $stats = $this->buildStats($gradeRows);

        return [
            'subjects' => $subjects,
            'selectedSubjectId' => $selectedSubjectId,
            'gradeRows' => $gradeRows,
            'stats' => $stats,
            'students' => Student::orderBy('last_name')->orderBy('first_name')->get(),
            'quarters' => Grade::QUARTERS,
            'modalMode' => null,
            'gradeFormStudent' => new Student,
            'gradeFormQuarters' => array_fill_keys(Grade::QUARTERS, null),
        ];
    }

    private function resolveSubjectId(Request $request, $subjects = null): ?int
    {
        $subjectId = $request->integer('subject_id') ?: null;

        if ($subjectId) {
            return $subjectId;
        }

        $subjects ??= Subject::orderBy('name')->get();

        return $subjects->first()?->id;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function buildGradeRows(?int $subjectId): array
    {
        if ($subjectId === null) {
            return [];
        }

        $grades = Grade::query()
            ->with('student.section')
            ->where('subject_id', $subjectId)
            ->orderBy('student_id')
            ->get()
            ->groupBy('student_id');

        $rows = [];

        foreach ($grades as $studentGrades) {
            $firstGrade = $studentGrades->first();

            if (!$firstGrade || !$firstGrade->student) {
                continue;
            }

            $student = $firstGrade->student;

            $quarterValues = [];

            foreach (Grade::QUARTERS as $quarter) {
                $record = $studentGrades->firstWhere('quarter', $quarter);

                $quarterValues[$quarter] = $record?->grade !== null
                    ? (float) $record->grade
                    : null;
            }

            $filled = array_filter($quarterValues, fn($value) => $value !== null);

            $average = $filled === []
                ? null
                : round(array_sum($filled) / count($filled), 1);

            $remarks = $average === null
                ? null
                : ($average >= Grade::PASSING_SCORE ? 'Passed' : 'Failed');

            $rows[] = [
                'student_id' => $student->id,
                'subject_id' => $subjectId,
                'name' => $student->full_name,
                'grades' => $quarterValues,
                'average' => $average,
                'remarks' => $remarks,
            ];
        }

        usort($rows, fn($a, $b) => strcasecmp($a['name'], $b['name']));

        return $rows;
    }

    /**
     * @param  array<int, array<string, mixed>>  $gradeRows
     * @return array{total: int, passed: int, failed: int, average: ?float}
     */
    private function buildStats(array $gradeRows): array
    {
        $withAverage = array_filter($gradeRows, fn($row) => $row['average'] !== null);
        $passed = count(array_filter($withAverage, fn($row) => $row['remarks'] === 'Passed'));
        $failed = count(array_filter($withAverage, fn($row) => $row['remarks'] === 'Failed'));

        $averages = array_column($withAverage, 'average');
        $classAverage = $averages === [] ? null : round(array_sum($averages) / count($averages), 1);

        return [
            'total' => count($gradeRows),
            'passed' => $passed,
            'failed' => $failed,
            'average' => $classAverage,
        ];
    }

    /**
     * @return array<string, ?float>
     */
    private function quarterGradesFor(int $studentId, ?int $subjectId): array
    {
        $values = array_fill_keys(Grade::QUARTERS, null);

        if ($subjectId === null) {
            return $values;
        }

        $records = Grade::query()
            ->where('student_id', $studentId)
            ->where('subject_id', $subjectId)
            ->get();

        foreach (Grade::QUARTERS as $quarter) {
            $record = $records->firstWhere('quarter', $quarter);
            $values[$quarter] = $record?->grade !== null ? (float) $record->grade : null;
        }

        return $values;
    }

    /**
     * @return array{student_id: int, subject_id: int, Q1: ?float, Q2: ?float, Q3: ?float, Q4: ?float}
     */
    private function validatedGradeData(Request $request): array
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'Q1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'Q2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'Q3' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'Q4' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach (Grade::QUARTERS as $quarter) {
            $value = $validated[$quarter] ?? null;
            $validated[$quarter] = $value === null || $value === '' ? null : (float) $value;
        }

        $hasQuarter = collect(Grade::QUARTERS)->contains(
            fn(string $quarter) => $request->filled($quarter)
        );

        if (!$hasQuarter) {
            throw ValidationException::withMessages([
                'Q1' => 'Enter a score for at least one quarter. Other quarters are optional.',
            ]);
        }

        return $validated;
    }

    /**
     * @param  array{student_id: int, subject_id: int, Q1: ?float, Q2: ?float, Q3: ?float, Q4: ?float}  $validated
     */
    private function syncQuarterGrades(array $validated): void
    {
        foreach (Grade::QUARTERS as $quarter) {
            $score = $validated[$quarter];

            if ($score === null) {
                continue;
            }

            Grade::query()->updateOrCreate(
                [
                    'student_id' => $validated['student_id'],
                    'subject_id' => $validated['subject_id'],
                    'quarter' => $quarter,
                ],
                [
                    'grade' => $score,
                    'is_final' => false,
                ]
            );
        }
    }
}
