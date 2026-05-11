@extends('layouts.index')

@section('content')
  <div class="gs-main-page">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-white text-2xl font-semibold">Grades</h1>
        <p class="gs-secondary-text text-sm">
          Manage and monitor all students grades
        </p>
      </div>
    </div>

    <div class="flex gap-4 justify-evenly">
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

      <x-search-filter />
    </div>

    {{-- CARDS --}}
    <div class="grid grid-cols-4 gap-4 mt-4">
      @php
        $cards = [
            ['name' => 'Total Students', 'value' => '3'],
            ['name' => 'Passed', 'value' => '2'],
            ['name' => 'Failed', 'value' => '1'],
            ['name' => 'Class Average', 'value' => '66.7%'],
        ];
      @endphp

      @foreach ($cards as $card)
        <div class="gs-card rounded-lg px-4 py-2">
          <h1 class="gs-secondary-text text-md">{{ $card['name'] }}</h1>
          <p class="gs-primary-text text-2xl font-semibold">{{ $card['value'] }}</p>
        </div>
      @endforeach
    </div>

    {{-- TABLE --}}
    @php
      $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];

      $items = [
          [
              'name' => 'Jerson Jay Bonghanoy',
              'grades' => [
                  'Q1' => 88,
                  'Q2' => 88,
                  'Q3' => 88,
                  'Q4' => 88,
              ],
              'average' => 88,
              'remarks' => 'Passed',
          ],
          [
              'name' => 'John Gave Dela Cerna',
              'grades' => [
                  'Q1' => 72,
                  'Q2' => 68,
                  'Q3' => 70,
                  'Q4' => 70,
              ],
              'average' => 70,
              'remarks' => 'Failed',
          ],
          [
              'name' => 'Mark Maturan',
              'grades' => [
                  'Q1' => 95,
                  'Q2' => 92,
                  'Q3' => 96,
                  'Q4' => 99,
              ],
              'average' => 94.3,
              'remarks' => 'Passed',
          ],
      ];
    @endphp

    <div class="gs-card rounded-lg overflow-x-auto border-[#545878] mt-6">

      <table class="min-w-full text-sm text-left">

        {{-- HEADER --}}
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

        {{-- BODY --}}
        <tbody class="divide-y divide-[#2E3350]">

          @foreach ($items as $index => $item)
            <tr class="hover:bg-[#22273D] transition">

              {{-- INDEX --}}
              <td class="px-4 py-3 gs-secondary-text">
                {{ $index + 1 }}
              </td>

              {{-- NAME --}}
              <td class="px-4 py-3 gs-primary-text font-medium">
                {{ $item['name'] }}
              </td>

              {{-- GRADES (DYNAMIC LOOP) --}}
              @foreach ($quarters as $q)
                <td class="px-4 py-3 gs-secondary-text">
                  {{ $item['grades'][$q] ?? '-' }}
                </td>
              @endforeach

              {{-- AVERAGE --}}
              <td class="px-4 py-3 gs-secondary-text">
                {{ $item['average'] }}
              </td>

              {{-- REMARKS --}}
              <td class="px-4 py-3">
                <span
                  class="px-2 py-1 rounded-lg border text-xs
                {{ $item['remarks'] === 'Passed' ? 'gs-success-bg gs-success-text ' : 'bg-[#3B2437] text-red-400' }}">
                  {{ $item['remarks'] }}
                </span>
              </td>

              {{-- ACTIONS --}}
              <td class="px-4 py-3 text-center">
                <div class="flex justify-center gap-2">

                  <button
                    class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    View
                  </button>

                  <button
                    class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border">
                    <i data-lucide="square-pen" class="w-4 h-4"></i>
                    Edit
                  </button>

                </div>
              </td>

            </tr>
          @endforeach

        </tbody>

      </table>
    </div>

  </div>
@endsection
