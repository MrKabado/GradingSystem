<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AppliesSectionFilters;
use App\Models\ActivityLog;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    use AppliesSectionFilters;

    public function index(Request $request): View
    {
        return view('sections.index', $this->indexPayload($request));
    }

    public function create(Request $request): View
    {
        return view('sections.index', array_merge($this->indexPayload($request), [
            'modalMode' => 'create',
            'sectionFormModel' => new Section,
        ]));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatedSectionData($request);

        $section = Section::create($validated);

        ActivityLog::record('created', 'sections', $section->id, 'Created section: '.$section->display_name);

        return redirect()
            ->route('sections.index')
            ->with('status', 'Section created successfully.');
    }

    public function edit(Request $request, Section $section): View
    {
        return view('sections.index', array_merge($this->indexPayload($request), [
            'modalMode' => 'edit',
            'sectionFormModel' => $section,
        ]));
    }

    public function update(Request $request, Section $section): RedirectResponse
    {
        $validated = $this->validatedSectionData($request);

        $section->update($validated);

        ActivityLog::record('updated', 'sections', $section->id, 'Updated section: '.$section->display_name);

        return redirect()
            ->route('sections.index')
            ->with('status', 'Section updated successfully.');
    }

    public function destroy(Section $section): RedirectResponse
    {
        $label = $section->display_name;
        $id = $section->id;
        $section->delete();

        ActivityLog::record('deleted', 'sections', $id, 'Deleted section: '.$label);

        return redirect()
            ->route('sections.index')
            ->with('status', 'Section removed successfully.');
    }

    /**
     * @return array<string, mixed>
     */
    private function indexPayload(Request $request): array
    {
        $filters = $this->sectionFilterParams($request);

        $sectionsQuery = Section::withCount('students')
            ->orderBy('year_level')
            ->orderBy('section');

        if ($filters['selectedYearLevel']) {
            $sectionsQuery->where('year_level', $filters['selectedYearLevel']);
        }

        if ($filters['selectedSection']) {
            $sectionsQuery->where('section', $filters['selectedSection']);
        }

        if ($filters['search']) {
            $search = $filters['search'];
            $sectionsQuery->where(function ($q) use ($search) {
                $q->where('year_level', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%")
                    ->orWhere('class_adviser', 'like', "%{$search}%");
            });
        }

        return array_merge($filters, [
            'sections' => $sectionsQuery
                ->paginate(15)
                ->withQueryString(),

            'modalMode' => null,
            'sectionFormModel' => new Section,
        ]);
    }

    /**
     * @return array{year_level: string, section: string, class_adviser: string}
     */
    private function validatedSectionData(Request $request): array
    {
        return $request->validate([
            'year_level' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
            'class_adviser' => ['required', 'string', 'max:255'],
        ]);
    }
}
