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

          <!-- Body -->
          <tbody class="divide-y divide-[#2E3350]">

            <tr class="hover:bg-[#22273D] transition">

              <td class="px-4 py-3 text-white">1</td>

              <td class="px-4 py-3 text-white font-medium">
                Jerson Jay Bonghanoy
              </td>

              <td class="px-4 py-3 gs-secondary-text">
                20230891
              </td>

              <td class="px-4 py-3 gs-secondary-text">
                Grade 7
              </td>

              <td class="px-4 py-3 gs-secondary-text">
                SECTION A
              </td>

              <td class="px-4 py-3">
                <span class="px-2 py-1 text-xs rounded-full bg-green-500/20 text-green-400">
                  Active
                </span>
              </td>

              <td class="px-4 py-3 text-center space-x-2">

                <button class="text-indigo-400 hover:text-indigo-300 transition">
                  Edit
                </button>

                <button class="text-red-400 hover:text-red-300 transition">
                  Delete
                </button>

              </td>

            </tr>

          </tbody>

        </table>

      </div>
    </div>
  </div>
@endsection
