<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SectionController extends Controller
{
    public function index(): View
    {
        return view('sections.index', $this->indexPayload());
    }

    public function create(): View
    {
        return view('sections.index', array_merge($this->indexPayload(), [
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

    public function edit(Section $section): View
    {
        return view('sections.index', array_merge($this->indexPayload(), [
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
    private function indexPayload(): array
    {
        return [
            'sections' => Section::withCount('students')
                ->orderBy('year_level')
                ->orderBy('section')
                ->paginate(15)
                ->withQueryString(),

            'modalMode' => null,
            'sectionFormModel' => new Section,
        ];
    }

    /**
     * @return array{year_level: string, section: string}
     */
    private function validatedSectionData(Request $request): array
    {
        return $request->validate([
            'year_level' => ['required', 'string', 'max:255'],
            'section' => ['required', 'string', 'max:255'],
        ]);
    }
}
