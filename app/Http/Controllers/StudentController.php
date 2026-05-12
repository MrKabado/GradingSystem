<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        return view('students.index', $this->indexPayload());
    }

    public function create(): View
    {
        return view('students.index', array_merge($this->indexPayload(), [
            'modalMode' => 'create',
            'studentFormModel' => new Student,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedStudentData($request);

        $student = Student::create($validated);

        ActivityLog::record('created', 'students', $student->id, 'Created student: '.$student->full_name);

        return redirect()
            ->route('students.index')
            ->with('status', 'Student created successfully.');
    }

    public function show(Student $student): View
    {
        $student->load('section');

        return view('students.show', compact('student'));
    }

    public function edit(Student $student): View
    {
        return view('students.index', array_merge($this->indexPayload(), [
            'modalMode' => 'edit',
            'studentFormModel' => $student->load('section'),
        ]));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $validated = $this->validatedStudentData($request, $student);

        $student->update($validated);

        ActivityLog::record('updated', 'students', $student->id, 'Updated student: '.$student->full_name);

        return redirect()
            ->route('students.index')
            ->with('status', 'Student updated successfully.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        $name = $student->full_name;
        $id = $student->id;
        $student->delete();

        ActivityLog::record('deleted', 'students', $id, 'Deleted student: '.$name);

        return redirect()
            ->route('students.index')
            ->with('status', 'Student removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexPayload(): array
    {
        return [
            'students' => Student::with('section')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->paginate(15)
                ->withQueryString(),
            'sections' => Section::orderBy('year_level')
                ->orderBy('section')
                ->get(),
            'modalMode' => null,
            'studentFormModel' => new Student,
        ];
    }

    /**
     * @return array{student_id: string, first_name: string, middle_name: ?string, last_name: string, section_id: ?int}
     */
    private function validatedStudentData(Request $request, ?Student $student = null): array
    {
        $studentIdRule = Rule::unique('students', 'student_id');

        if ($student !== null) {
            $studentIdRule = $studentIdRule->ignore($student->id);
        }

        return $request->validate([
            'student_id' => ['required', 'string', 'max:255', $studentIdRule],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'section_id' => ['nullable', 'exists:sections,id'],
        ]);
    }
}
