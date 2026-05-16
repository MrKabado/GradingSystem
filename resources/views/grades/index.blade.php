@extends('layouts.index')

@section('content')
  <div class="gs-main-page">
    @if (session('status'))
      <div class="rounded-lg border border-[#31326E] bg-[#1E1F44] px-4 py-3 text-sm text-[#8B84FF]">
        {{ session('status') }}
      </div>
    @endif

    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-white text-2xl font-semibold">Grades</h1>
        <p class="gs-secondary-text text-sm">
          Manage and monitor all students grades
        </p>
      </div>

      @if ($subjects->isNotEmpty())
        <a href="{{ route('grades.create', ['subject_id' => $selectedSubjectId]) }}"
          class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 rounded-lg cursor-pointer text-sm py-2 inline-flex items-center">
          Add Grades
        </a>
      @endif
    </div>

    <form method="GET" action="{{ route('grades.index') }}" class="flex flex-wrap gap-4 items-end">
      <div class="min-w-[200px]">
        <label for="subject_id" class="block text-xs font-medium text-gray-400 mb-1">Subject</label>
        <select id="subject_id" name="subject_id" onchange="this.form.submit()"
          class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]">
          @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" @selected((int) $selectedSubjectId === (int) $subject->id)>
              {{ $subject->name }}
            </option>
          @endforeach
        </select>
      </div>

      <x-search-filter />

      <x-dropdown selectName="Grade Level" :options="[
      '7' => 'Grade 7',
      '8' => 'Grade 8',
      '9' => 'Grade 9',
      '10' => 'Grade 10',
    ]" />

      <x-dropdown selectName="Section" :options="[
      'A' => 'Section A',
      'B' => 'Section B',
      'C' => 'Section C',
      'D' => 'Section D',
    ]" />
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
      <div class="gs-card rounded-lg px-4 py-2">
        <h2 class="gs-secondary-text text-md">Total Students</h2>
        <p class="gs-primary-text text-2xl font-semibold">{{ $stats['total'] }}</p>
      </div>
      <div class="gs-card rounded-lg px-4 py-2">
        <h2 class="gs-secondary-text text-md">Passed</h2>
        <p class="gs-primary-text text-2xl font-semibold">{{ $stats['passed'] }}</p>
      </div>
      <div class="gs-card rounded-lg px-4 py-2">
        <h2 class="gs-secondary-text text-md">Failed</h2>
        <p class="gs-primary-text text-2xl font-semibold">{{ $stats['failed'] }}</p>
      </div>
      <div class="gs-card rounded-lg px-4 py-2">
        <h2 class="gs-secondary-text text-md">Class Average</h2>
        <p class="gs-primary-text text-2xl font-semibold">
          {{ $stats['average'] !== null ? $stats['average'] . '%' : '—' }}
        </p>
      </div>
    </div>

    <div class="gs-card rounded-lg overflow-x-auto border-[#545878] mt-6">
      <table class="min-w-full text-sm text-left">
        <thead class="bg-[#1C2035] border-b border-t border-[#545878]">
          <tr class="gs-secondary-text text-xs uppercase tracking-wider">
            <th class="px-4 py-3">#</th>
            <th class="px-4 py-3">Student Name</th>
            @foreach ($quarters as $q)
              <th class="px-4 py-3">{{ $q }}</th>
            @endforeach
            <th class="px-4 py-3">Average</th>
            <th class="px-4 py-3">Remarks</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
        </thead>

        <tbody class="divide-y divide-[#2E3350]">
          @forelse ($gradeRows as $index => $item)
            <tr class="hover:bg-[#22273D] transition">
              <td class="px-4 py-3 gs-secondary-text">{{ $index + 1 }}</td>
              <td class="px-4 py-3 gs-primary-text font-medium">{{ $item['name'] }}</td>
              @foreach ($quarters as $q)
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $item['grades'][$q] ?? '—' }}
                </td>
              @endforeach
              <td class="px-4 py-3 gs-secondary-text">
                {{ $item['average'] ?? '—' }}
              </td>
              <td class="px-4 py-3">
                @if ($item['remarks'])
                  <span
                    class="px-2 py-1 rounded-lg border text-xs
                          {{ $item['remarks'] === 'Passed' ? 'gs-success-bg gs-success-text' : 'bg-[#3B2437] text-red-400' }}">
                    {{ $item['remarks'] }}
                  </span>
                @else
                  <span class="gs-secondary-text">—</span>
                @endif
              </td>
              <td class="px-4 py-3 text-center">
                <div class="flex justify-center gap-2 flex-wrap">
                  <a href="{{ route('grades.show', ['student' => $item['student_id'], 'subject_id' => $selectedSubjectId]) }}"
                    class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    View
                  </a>
                  <a href="{{ route('grades.edit', ['student' => $item['student_id'], 'subject_id' => $selectedSubjectId]) }}"
                    class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Edit
                  </a>
                  <form method="POST"
                    action="{{ route('grades.destroy', ['student' => $item['student_id'], 'subject_id' => $selectedSubjectId]) }}"
                    class="inline" onsubmit="return confirm('Remove all quarter grades for this student?');">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}" />
                    <button type="submit"
                      class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                      Delete
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="{{ 5 + count($quarters) }}" class="px-4 py-8 text-center gs-secondary-text">
                @if ($subjects->isEmpty())
                  Add a subject first, then record grades here.
                @else
                  No grades for this subject yet. Use <span class="text-[#8B84FF]">Add Grades</span> to enter scores.
                @endif
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if ($modalMode)
      @php
        $isEdit = $modalMode === 'edit';
        $isView = $modalMode === 'view';
        $formAction = $isEdit
          ? route('grades.update', ['student' => $gradeFormStudent, 'subject_id' => $selectedSubjectId])
          : route('grades.store');
        $quarterValues = $gradeFormQuarters ?? array_fill_keys($quarters, null);
      @endphp

      <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" role="dialog" aria-modal="true"
        aria-labelledby="grade-modal-title">
        <div
          class="gs-card w-full max-w-lg rounded-xl shadow-xl border border-[#545878] bg-[#13162A] max-h-[90vh] overflow-y-auto">
          <div class="flex items-start justify-between gap-4 border-b border-[#545878] px-5 py-4">
            <div>
              <h2 id="grade-modal-title" class="text-lg font-semibold text-white">
                @if ($isView)
                  View grades
                @elseif ($isEdit)
                  Edit grades
                @else
                  Add grades
                @endif
              </h2>
              <p class="text-xs gs-secondary-text mt-1">
                @if ($isView)
                  Quarter scores for this student.
                @elseif ($isEdit)
                  Update one or more quarters. Leave a field blank to keep its current score.
                @else
                  Select a student and enter at least one quarter score. Other quarters are optional.
                @endif
              </p>
            </div>
            <a href="{{ route('grades.index', ['subject_id' => $selectedSubjectId]) }}"
              class="rounded-lg p-2 text-[#545878] hover:bg-[#22273D] hover:text-white transition" aria-label="Close">
              <i data-lucide="x" class="w-5 h-5"></i>
            </a>
          </div>

          @if ($isView)
            <div class="px-5 py-4 space-y-4">
              <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Student</p>
                <p class="text-sm text-white">{{ $gradeFormStudent->full_name }}</p>
              </div>
              <div>
                <p class="text-xs font-medium text-gray-400 mb-1">Subject</p>
                <p class="text-sm text-white">
                  {{ $subjects->firstWhere('id', $selectedSubjectId)?->name ?? '—' }}
                </p>
              </div>
              <div class="grid grid-cols-2 gap-4">
                @foreach ($quarters as $q)
                  <div>
                    <p class="text-xs font-medium text-gray-400 mb-1">{{ $q }}</p>
                    <p class="text-sm text-white">{{ $quarterValues[$q] ?? '—' }}</p>
                  </div>
                @endforeach
              </div>
              @php
                $filled = array_filter($quarterValues, fn($v) => $v !== null);
                $viewAverage = $filled === [] ? null : round(array_sum($filled) / count($filled), 1);
                $viewRemarks = $viewAverage === null ? null : ($viewAverage >= \App\Models\Grade::PASSING_SCORE ? 'Passed' : 'Failed');
              @endphp
              <div class="grid grid-cols-2 gap-4 border-t border-[#545878] pt-4">
                <div>
                  <p class="text-xs font-medium text-gray-400 mb-1">Average</p>
                  <p class="text-sm text-white">{{ $viewAverage ?? '—' }}</p>
                </div>
                <div>
                  <p class="text-xs font-medium text-gray-400 mb-1">Remarks</p>
                  @if ($viewRemarks)
                    <span class="px-2 py-1 rounded-lg border text-xs inline-block
                              {{ $viewRemarks === 'Passed' ? 'gs-success-bg gs-success-text' : 'bg-[#3B2437] text-red-400' }}">
                      {{ $viewRemarks }}
                    </span>
                  @else
                    <p class="text-sm text-white">—</p>
                  @endif
                </div>
              </div>
              <div class="flex justify-end border-t border-[#545878] pt-4">
                <a href="{{ route('grades.index', ['subject_id' => $selectedSubjectId]) }}"
                  class="gs-secondary-btn text-sm py-2 px-4 inline-flex items-center justify-center">
                  Close
                </a>
              </div>
            </div>
          @else
            <form method="POST" action="{{ $formAction }}" class="px-5 py-4 space-y-4">
              @csrf
              @if ($isEdit)
                @method('PUT')
              @endif

              <input type="hidden" name="subject_id" value="{{ $selectedSubjectId }}" />

              <div>
                <label for="student_id" class="block text-xs font-medium text-gray-400 mb-1">Student</label>
                <select id="student_id" name="student_id" required @disabled($isEdit)
                  class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1] disabled:opacity-60">
                  <option value="">— Select student —</option>
                  @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected((string) old('student_id', $gradeFormStudent->id) === (string) $student->id)>
                      {{ $student->full_name }}
                    </option>
                  @endforeach
                </select>
                @if ($isEdit)
                  <input type="hidden" name="student_id" value="{{ $gradeFormStudent->id }}" />
                @endif
                @error('student_id')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <p class="block text-xs font-medium text-gray-400 mb-1">Subject</p>
                <p class="text-sm text-white">
                  {{ $subjects->firstWhere('id', $selectedSubjectId)?->name ?? '—' }}
                </p>
              </div>

              <div class="grid grid-cols-2 gap-4">
                @foreach ($quarters as $q)
                  <div>
                    <label for="{{ $q }}" class="block text-xs font-medium text-gray-400 mb-1">
                      {{ $q }} <span class="text-[#545878] font-normal">(optional)</span>
                    </label>
                    <input id="{{ $q }}" name="{{ $q }}" type="number" min="0" max="100" step="0.01"
                      value="{{ old($q, $quarterValues[$q] ?? '') }}" placeholder="—"
                      class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white placeholder-[#545878] focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]" />
                    @error($q)
                      <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                  </div>
                @endforeach
              </div>
              @error('Q1')
                @if (!$errors->has('Q2') && !$errors->has('Q3') && !$errors->has('Q4'))
                  <p class="text-xs text-red-400">{{ $message }}</p>
                @endif
              @enderror

              <div class="flex flex-wrap justify-end gap-2 border-t border-[#545878] pt-4">
                <a href="{{ route('grades.index', ['subject_id' => $selectedSubjectId]) }}"
                  class="gs-secondary-btn text-sm py-2 px-4 inline-flex items-center justify-center">
                  Cancel
                </a>
                <button type="submit"
                  class="bg-[#6366F1] hover:bg-[#5558e8] text-white text-sm font-semibold py-2 px-4 rounded-lg">
                  {{ $isEdit ? 'Save changes' : 'Save grades' }}
                </button>
              </div>
            </form>
          @endif
        </div>
      </div>
    @endif
  </div>
@endsection