@extends('layouts.index')

@section('content')
  <div class="gs-main-page">
    {{-- HEADING PART --}}
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-white text-2xl font-semibold">Subjects</h1>
        <p class="gs-secondary-text text-sm">Manage subjects by section and year level</p>
      </div>

      <div class="flex items-center gap-4">
        <x-button class="text-sm py-2">
          Add Year Level
        </x-button>

        <x-button class="text-sm py-2">
          Add Section
        </x-button>
      </div>
    </div>

    {{-- SAMPLE DATA --}}
    @php
      $years = [
        [
          'name' => 'Year 7',
          'sections' => [
            [
              'name' => 'Section A',
              'subjects' => [
                [
                  'name' => 'Mathematics',
                  'teacher' => 'Mr. Octobre',
                  'passedRate' => '92',
                ],
                [
                  'name' => 'English',
                  'teacher' => 'Mr. Casagan',
                  'passedRate' => '85.5',
                ],
                [
                  'name' => 'Science',
                  'teacher' => 'Mr. Casil',
                  'passedRate' => '90.1',
                ],
                [
                  'name' => 'MAPEH',
                  'teacher' => 'Mr. Balungag',
                  'passedRate' => '25.5',
                ],
              ]
            ],
          ]
        ],
      ];
    @endphp

    {{-- YEAR LEVEL LOOP --}}
    @foreach ($years as $year)
      <div class="gs-card rounded-md px-4 py-2 mt-4">

        {{-- YEAR HEADER --}}
        <div class="flex items-center justify-between mb-4">
          <div class="flex gap-2 items-center">
            <i data-lucide="graduation-cap" class="text-[#6C63FF] w-8 h-8" stroke-width="1.5"></i>
            <h1 class="text-gray-300 text-lg font-semibold">{{ $year['name'] }}</h1>
          </div>

          <div class="flex items-center gap-2">
            <span class="border gs-primary-border-color gs-tertiary-bg gs-secondary-text px-2 rounded-lg text-sm">
              {{ count($year['sections']) }} Sections
            </span>
            <i data-lucide="chevron-down" class="text-gray-300"></i>
          </div>
        </div>

        {{-- SECTION LOOP --}}
        @foreach ($year['sections'] as $section)
          <div class="gs-card px-4 py-4 rounded-lg mb-4">

            {{-- SECTION HEADER --}}
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <i data-lucide="users-round" class="gs-icon-blue"></i>
                <h1 class="gs-primary-text font-semibold">{{ $section['name'] }}</h1>
              </div>

              <div class="flex items-center gap-2">
                <span class="border gs-primary-border-color gs-tertiary-bg gs-secondary-text px-2 py-1 rounded-lg text-sm">
                  {{ count($section['subjects']) }} Subjects
                </span>

                <x-button class="text-sm py-1">
                  Add Subject
                </x-button>

                <i data-lucide="ellipsis-vertical" class="gs-primary-text"></i>
              </div>
            </div>

            {{-- SUBJECTS GRID --}}
            <div class="grid grid-cols-3 gap-4 mt-4">

              {{-- SUBJECT LOOP --}}
              @foreach ($section['subjects'] as $subject)
                <div class="gs-card p-4 rounded-lg flex flex-col gap-1">
                  <h1 class="gs-primary-text text-xl font-semibold">
                    {{ $subject['name'] }}
                  </h1>

                  <p class="gs-secondary-text text-sm">
                    Teacher: {{ $subject['teacher'] }}
                  </p>

                  <span class="gs-success-text gs-success-bg text-xs px-2 rounded-md w-fit">
                    Passed: {{ $subject['passedRate'] }}%
                  </span>
                </div>
              @endforeach

            </div>
          </div>
        @endforeach

      </div>
    @endforeach

  </div>
@endsection