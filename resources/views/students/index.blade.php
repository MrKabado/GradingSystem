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
        <h1 class="text-white text-2xl font-semibold">Students</h1>
        <p class="gs-secondary-text text-sm">Manage and monitor all enrolled students</p>
      </div>

      <a href="{{ route('students.create') }}"
        class="bg-indigo-500 hover:bg-indigo-600 text-white px-6 rounded-lg cursor-pointer text-sm py-2 inline-flex items-center">
        Add Student
      </a>
    </div>

    <div class="flex gap-4 justify-evenly">
      <x-search-filter />

      <x-dropdown selectName="GradeLevels" :options="[
          '7' => 'Grade 7',
          '8' => 'Grade 8',
          '9' => 'Grade 9',
          '10' => 'Grade 10',
      ]" />

      <x-dropdown selectName="Sections" :options="[
          'A' => 'Section A',
          'B' => 'Section B',
          'C' => 'Section C',
          'D' => 'Section D',
      ]" />
    </div>

    <div class="gs-card py-4 rounded-lg space-y-4">
      <div class="flex justify-between items-center px-4">
        <div>
          <h1 class="text-xl font-semibold text-gray-300">Student List</h1>
          <p class="text-xs gs-secondary-text mt-1">Manage and monitor all students</p>
        </div>

        <div class="text-[#8B84FF] bg-[#1E1F44] px-3 py-1 rounded-lg">
          <h1 class="text-xs">{{ $students->total() }} {{ $students->total() === 1 ? 'Student' : 'Students' }}</h1>
        </div>
      </div>

      <div class="overflow-x-auto border-[#545878]">
        <table class="min-w-full text-sm text-left">
          <thead class="bg-[#1C2035] border-b border-t border-[#545878]">
            <tr class="gs-secondary-text text-xs uppercase tracking-wider">
              <th class="px-4 py-3">#</th>
              <th class="px-4 py-3">Student Name</th>
              <th class="px-4 py-3">Student ID</th>
              <th class="px-4 py-3">Grade Level</th>
              <th class="px-4 py-3">Section</th>
              <th class="px-4 py-3">Status</th>
              <th class="px-4 py-3 text-center">Actions</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-[#2E3350]">
            @forelse ($students as $student)
              <tr class="hover:bg-[#22273D] transition">
                <td class="px-4 py-3 text-white">{{ $students->firstItem() + $loop->index }}</td>
                <td class="px-4 py-3 text-white font-medium">
                  <a href="{{ route('students.show', $student) }}" class="hover:text-[#8B84FF] transition">
                    {{ $student->full_name }}
                  </a>
                </td>
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $student->student_id }}
                </td>
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $student->section ? 'Grade '.$student->section->year_level : '—' }}
                </td>
                <td class="px-4 py-3">
                  @if ($student->section)
                    <span
                      class="border-[0.5px] border-[#31326E] bg-[#23264A] text-[#8B84FF] w-fit px-2 py-1 rounded-lg">
                      {{ $student->section->section }}
                    </span>
                  @else
                    <span class="gs-secondary-text">—</span>
                  @endif
                </td>
                <td class="px-4 py-3 gap-1">
                  <div class="flex items-center gap-1">
                    <span
                      class="flex items-center w-fit px-1 py-1 text-xs border-2 rounded-full bg-green-400 border-[#152B30]">
                    </span>
                    <p class="gs-secondary-text">Active</p>
                  </div>
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                  <div class="flex items-center justify-center gap-2 flex-wrap">
                    <a href="{{ route('students.edit', $student) }}"
                      class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                      <i data-lucide="square-pen" class="w-4 h-4"></i>
                      Edit
                    </a>
                    <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline"
                      onsubmit="return confirm('Remove this student from the list?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                        class="flex items-center gap-1 h-full gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-8 text-center gs-secondary-text">
                  No students yet. Use <span class="text-[#8B84FF]">Add Student</span> to enroll one.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($students->hasPages())
        <div class="px-4 pb-2 text-sm gs-secondary-text [&_a]:text-[#8B84FF] [&_span]:text-white">
          {{ $students->links() }}
        </div>
      @endif
    </div>

    @if ($modalMode)
      @php
        $isEdit = $modalMode === 'edit';
        $formAction = $isEdit ? route('students.update', $studentFormModel) : route('students.store');
      @endphp

      <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60" role="dialog"
        aria-modal="true" aria-labelledby="student-modal-title">
        <div
          class="gs-card w-full max-w-lg rounded-xl shadow-xl border border-[#545878] bg-[#13162A] max-h-[90vh] overflow-y-auto">
          <div class="flex items-start justify-between gap-4 border-b border-[#545878] px-5 py-4">
            <div>
              <h2 id="student-modal-title" class="text-lg font-semibold text-white">
                {{ $isEdit ? 'Edit student' : 'Add student' }}
              </h2>
              <p class="text-xs gs-secondary-text mt-1">
                {{ $isEdit ? 'Update enrollment details below.' : 'Fill in the details to enroll a new student.' }}
              </p>
            </div>
            <a href="{{ route('students.index') }}"
              class="rounded-lg p-2 text-[#545878] hover:bg-[#22273D] hover:text-white transition"
              aria-label="Close">
              <i data-lucide="x" class="w-5 h-5"></i>
            </a>
          </div>

          <form method="POST" action="{{ $formAction }}" class="px-5 py-4 space-y-4">
            @csrf
            @if ($isEdit)
              @method('PUT')
            @endif

            <div>
              <label for="student_id" class="block text-xs font-medium text-gray-400 mb-1">Student ID</label>
              <input id="student_id" name="student_id" type="text" required
                value="{{ old('student_id', $studentFormModel->student_id) }}"
                class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white placeholder-[#545878] focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]" />
              @error('student_id')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label for="first_name" class="block text-xs font-medium text-gray-400 mb-1">First name</label>
                <input id="first_name" name="first_name" type="text" required
                  value="{{ old('first_name', $studentFormModel->first_name) }}"
                  class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]" />
                @error('first_name')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label for="middle_name" class="block text-xs font-medium text-gray-400 mb-1">Middle name</label>
                <input id="middle_name" name="middle_name" type="text"
                  value="{{ old('middle_name', $studentFormModel->middle_name) }}"
                  class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]" />
                @error('middle_name')
                  <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label for="last_name" class="block text-xs font-medium text-gray-400 mb-1">Last name</label>
              <input id="last_name" name="last_name" type="text" required
                value="{{ old('last_name', $studentFormModel->last_name) }}"
                class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]" />
              @error('last_name')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label for="section_id" class="block text-xs font-medium text-gray-400 mb-1">Section (optional)</label>
              <select id="section_id" name="section_id"
                class="w-full rounded-lg border border-[#545878] bg-[#0D0F1A] px-3 py-2 text-sm text-white focus:border-[#6366F1] focus:outline-none focus:ring-1 focus:ring-[#6366F1]">
                <option value="">— None —</option>
                @foreach ($sections as $section)
                  <option value="{{ $section->id }}"
                    @selected((string) old('section_id', $studentFormModel->section_id) === (string) $section->id)>
                    {{ $section->display_name }}
                  </option>
                @endforeach
              </select>
              @error('section_id')
                <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
              @enderror
            </div>

            <div class="flex flex-wrap justify-end gap-2 border-t border-[#545878] pt-4">
              <a href="{{ route('students.index') }}"
                class="gs-secondary-btn text-sm py-2 px-4 inline-flex items-center justify-center">
                Cancel
              </a>
              <button type="submit"
                class="bg-[#6366F1] hover:bg-[#5558e8] text-white text-sm font-semibold py-2 px-4 rounded-lg">
                {{ $isEdit ? 'Save changes' : 'Create student' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    @endif
  </div>
@endsection
