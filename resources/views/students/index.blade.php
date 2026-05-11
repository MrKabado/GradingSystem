@extends('layouts.index')

@section('content')
  <div class="gs-primary-bg h-full p-6 space-y-8">
    {{-- TITLE and ADD STUDENT BTN --}}
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-white text-2xl font-semibold">Students</h1>
        <p class="gs-secondary-text text-sm">Manage and monitor all enrolled students</p>
      </div>

      <x-button class="text-sm">
        Add Student
      </x-button>
    </div>

    {{-- SEARCH and DROPDOWN --}}
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

    {{-- TABLE --}}
    <div class="gs-card py-4 rounded-lg space-y-4">

      <!-- Header -->
      <div class="flex justify-between items-center px-4">

        <div>
          <h1 class="text-xl font-semibold text-gray-300">Student List</h1>
          <p class="text-xs gs-secondary-text mt-1">Manage and monitor all students</p>
        </div>

        <div class="text-[#8B84FF] bg-[#1E1F44] px-3 py-1 rounded-lg">
          <h1 class="text-xs">3 Students</h1>
        </div>

      </div>

      <!-- Table -->
      <div class="overflow-x-auto border-[#545878]">
        <table class="min-w-full text-sm text-left">
          <!-- Header -->
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

          @php
            $items = [
                [
                    'name' => 'Jerson Jay Bonghanoy',
                    'studentId' => '20230891',
                    'gradeLevel' => 'Grade 7',
                    'section' => 'Section A',
                    'status' => 'Active',
                ],
                [
                    'name' => 'John Gave Dela Cerna',
                    'studentId' => '20230892',
                    'gradeLevel' => 'Grade 7',
                    'section' => 'Section A',
                    'status' => 'Active',
                ],
                [
                    'name' => 'Mark Maturan',
                    'studentId' => '20230893',
                    'gradeLevel' => 'Grade 7',
                    'section' => 'Section A',
                    'status' => 'Inactive',
                ],
            ];
          @endphp

          <!-- Body -->
          <tbody class="divide-y divide-[#2E3350]">

            @foreach ($items as $index => $item)
              <tr class="hover:bg-[#22273D] transition">
                <td class="px-4 py-3 text-white">{{ $index }}</td>
                <td class="px-4 py-3 text-white font-medium">
                  {{ $item['name'] }}
                </td>
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $item['studentId'] }}
                </td>
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $item['gradeLevel'] }}
                </td>
                <td class="px-4 py-3">
                  <span class="border-[0.5px] border-[#31326E] bg-[#23264A] text-[#8B84FF] w-fit px-2 py-1 rounded-lg">
                    {{ $item['section'] }}
                  </span>
                </td>
                <td class="px-4 py-3 gap-1">
                  <div class="flex items-center gap-1">
                    <span
                      class="flex items-center w-fit px-1 py-1 text-xs border-2 rounded-full
  {{ $item['status'] === 'Active' ? 'bg-green-400 border-[#152B30]' : 'bg-red-400 border-[#152B30]' }}">
                    </span>

                    <p class="gs-secondary-text">{{ $item['status'] }}</p>
                  </div>
                </td>
                <td class="px-4 py-3 text-center space-x-2">
                  <div class="flex items-center gap-2">
                    <button
                      class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                      <i data-lucide="square-pen" class="w-4 h-4"></i>
                      Edit
                    </button>
                    <button
                      class="flex items-center gap-1 h-full gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                      Delete
                    </button>
                  </div>
                </td>
              </tr>
            @endforeach

          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
