@extends('layouts.index')

@section('content')

  <div class="gs-main-page">
    <div class="">
      <h1 class="text-white text-2xl font-semibold">Grade Reports</h1>
      <p class="gs-secondary-text text-sm">View and download student grade reports</p>
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

    <div class="grid grid-cols-3 gap-4 mt-4">
      @php
        $cards = [
          ['name' => 'Total Reports', 'value' => '3'],
          ['name' => 'Available', 'value' => '2'],
          ['name' => 'Pending', 'value' => '1'],
        ];
      @endphp

      @foreach ($cards as $card)
        <div class="gs-card rounded-lg px-4 py-2">
          <h1 class="gs-secondary-text text-md">{{ $card['name'] }}</h1>
          <p class="gs-primary-text text-2xl font-semibold">{{ $card['value'] }}</p>
        </div>
      @endforeach
    </div>

    @php
      $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];

      $items = [
        [
          'name' => 'Jerson Jay Bonghanoy',
          'average' => 88,
          'status' => 'Available',
          'date-generated' => 'May 10, 2026'
        ],
        [
          'name' => 'John Gave Dela Cerna',
          'average' => 70,
          'status' => 'Pending',
          'date-generated' => '-'
        ],
        [
          'name' => 'Mark Maturan',
          'average' => 94.3,
          'status' => 'Available',
          'date-generated' => 'May 10, 2026'
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
            <th class="px-4 py-3">Section</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Date Generated</th>
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

              {{-- SECTION --}}
              <td class="px-4 py-3 gs-secondary-text">
                {{ $item['average'] }}
              </td>

              {{-- STATUS --}}
              <td class="px-4 py-3">
                <span
                  class="px-2 py-1 rounded-lg border text-xs
                        {{ $item['status'] === 'Available' ? 'gs-success-bg gs-success-text ' : 'bg-[#3B2437] text-red-400' }}">
                  {{ $item['status'] }}
                </span>
              </td>

              {{-- DATE GENERATED --}}
              <td class="px-4 py-3 gs-secondary-text">
                {{ $item['date-generated'] }}
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