<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(): View
    {
        return view('subjects.index', $this->indexPayload());
    }

    public function create(): View
    {
        return view('subjects.index', array_merge($this->indexPayload(), [
            'modalMode' => 'create',
            'subjectFormModel' => new Subject,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedSubjectData($request);

        $subject = Subject::create($validated);

        ActivityLog::record('created', 'subjects', $subject->id, 'Created subject: '.$subject->name);

        return redirect()
            ->route('subjects.index')
            ->with('status', 'Subject created successfully.');
    }

    public function edit(Subject $subject): View
    {
        return view('subjects.index', array_merge($this->indexPayload(), [
            'modalMode' => 'edit',
            'subjectFormModel' => $subject->load(['section', 'teacher']),
        ]));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $this->validatedSubjectData($request);

        $subject->update($validated);

        ActivityLog::record('updated', 'subjects', $subject->id, 'Updated subject: '.$subject->name);

        return redirect()
            ->route('subjects.index')
            ->with('status', 'Subject updated successfully.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $name = $subject->name;
        $id = $subject->id;
        $subject->delete();

        ActivityLog::record('deleted', 'subjects', $id, 'Deleted subject: '.$name);

        return redirect()
            ->route('subjects.index')
            ->with('status', 'Subject removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexPayload(): array
    {
        return [
            'subjects' => Subject::with(['section', 'teacher'])
                ->orderBy('name')
                ->paginate(15)
                ->withQueryString(),

            'sections' => Section::orderBy('year_level')
                ->orderBy('section')
                ->get(),

            'teachers' => Teacher::orderBy('name')->get(),

            'modalMode' => null,
            'subjectFormModel' => new Subject,
        ];
    }

    /**
     * @return array{name: string, section_id: ?int, teacher_id: ?int}
     */
    private function validatedSubjectData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'teacher_id' => ['nullable', 'exists:teachers,id'],
        ]);
    }
}
