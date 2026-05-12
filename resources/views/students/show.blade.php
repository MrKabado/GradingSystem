@extends('layouts.index')

@section('content')
  <div class="gs-main-page max-w-2xl">
    <div class="mb-6">
      <a href="{{ route('students.index') }}"
        class="inline-flex items-center gap-2 text-sm text-[#8B84FF] hover:text-white transition">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        Back to students
      </a>
    </div>

    <div class="gs-card rounded-xl p-6 space-y-6">
      <div class="flex flex-wrap items-start justify-between gap-4 border-b border-[#545878] pb-6">
        <div>
          <h1 class="text-2xl font-semibold text-white">{{ $student->full_name }}</h1>
          <p class="mt-1 text-sm gs-secondary-text">Student ID: <span class="text-gray-300">{{ $student->student_id }}</span></p>
        </div>
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('students.edit', $student) }}"
            class="inline-flex items-center gap-2 rounded-lg border gs-primary-border-color bg-[#22273D] px-3 py-2 text-sm text-gray-300 hover:bg-[#2B304A]">
            <i data-lucide="square-pen" class="w-4 h-4"></i>
            Edit
          </a>
        </div>
      </div>

      <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <dt class="text-xs font-medium uppercase tracking-wider gs-secondary-text">First name</dt>
          <dd class="mt-1 text-white">{{ $student->first_name }}</dd>
        </div>
        <div>
          <dt class="text-xs font-medium uppercase tracking-wider gs-secondary-text">Middle name</dt>
          <dd class="mt-1 text-white">{{ $student->middle_name ?: '—' }}</dd>
        </div>
        <div>
          <dt class="text-xs font-medium uppercase tracking-wider gs-secondary-text">Last name</dt>
          <dd class="mt-1 text-white">{{ $student->last_name }}</dd>
        </div>
        <div>
          <dt class="text-xs font-medium uppercase tracking-wider gs-secondary-text">Grade level</dt>
          <dd class="mt-1 text-white">{{ $student->section ? 'Grade '.$student->section->year_level : '—' }}</dd>
        </div>
        <div class="sm:col-span-2">
          <dt class="text-xs font-medium uppercase tracking-wider gs-secondary-text">Section</dt>
          <dd class="mt-1">
            @if ($student->section)
              <span
                class="inline-block border-[0.5px] border-[#31326E] bg-[#23264A] text-[#8B84FF] px-3 py-1 rounded-lg text-sm">
                {{ $student->section->display_name }}
              </span>
            @else
              <span class="text-gray-400">—</span>
            @endif
          </dd>
        </div>
      </dl>
    </div>
  </div>
@endsection
