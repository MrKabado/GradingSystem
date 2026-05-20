<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSectionFilters;
use App\Models\ActivityLog;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SubjectController extends Controller
{
    use AppliesSectionFilters;

    public function index(Request $request): View
    {
        return view('subjects.index', $this->indexPayload($request));
    }

    public function create(Request $request): View
    {
        return view('subjects.index', array_merge($this->indexPayload($request), [
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

    public function edit(Request $request, Subject $subject): View
    {
        return view('subjects.index', array_merge($this->indexPayload($request), [
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
    private function indexPayload(Request $request): array
    {
        $filters = $this->sectionFilterParams($request);

        $subjectsQuery = Subject::with(['section', 'teacher'])->orderBy('name');

        if ($filters['selectedYearLevel']) {
            $subjectsQuery->whereHas('section', function ($q) use ($filters) {
                $q->where('year_level', $filters['selectedYearLevel']);
            });
        }

        if ($filters['selectedSection']) {
            $subjectsQuery->whereHas('section', function ($q) use ($filters) {
                $q->where('section', $filters['selectedSection']);
            });
        }

        if ($filters['search']) {
            $search = $filters['search'];
            $subjectsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('teacher', function ($t) use ($search) {
                        $t->where('name', 'like', "%{$search}%");
                    });
            });
        }

        return array_merge($filters, [
            'subjects' => $subjectsQuery
                ->paginate(15)
                ->withQueryString(),

            'sections' => Section::orderBy('year_level')
                ->orderBy('section')
                ->get(),

            'modalMode' => null,
            'subjectFormModel' => new Subject,
        ]);
    }

    /**
     * @return array{name: string, section_id: ?int, teacher_id: ?int}
     */
    private function validatedSubjectData(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section_id' => ['nullable', 'exists:sections,id'],
            'teacher' => ['nullable', 'string', 'max:255'],
        ]);

        $teacherName = trim($validated['teacher'] ?? '');
        unset($validated['teacher']);

        $validated['teacher_id'] = $teacherName === ''
            ? null
            : Teacher::firstOrCreate(['name' => $teacherName])->id;

        return $validated;
    }
}
