@extends('layouts.index')

@section('content')

  <div class="gs-main-page space-y-6">
    <div>
      <h1 class="text-white text-2xl font-semibold">Grade Reports</h1>
      <p class="gs-secondary-text text-sm">View and download student grade reports</p>
    </div>

    @if (session('status'))
      <div class="rounded-lg border border-[#31326E] bg-[#1E1F44] px-4 py-3 text-sm text-[#8B84FF]">
        {{ session('status') }}
      </div>
    @endif

    <x-section-filters
      :action="route('grade-reports.index')"
      :reset-url="route('grade-reports.index')"
      :year-levels="$yearLevels"
      :section-names="$sectionNames"
      :selected-year-level="$selectedYearLevel"
      :selected-section="$selectedSection"
      search-placeholder="Search by student name or ID..."
    />

    <div class="grid grid-cols-3 gap-4">
      @foreach ($cards as $card)
        <div class="gs-card rounded-lg px-4 py-3 flex flex-col justify-between">
          <h1 class="gs-secondary-text text-xs uppercase tracking-wider font-semibold">{{ $card['name'] }}</h1>
          <p class="gs-primary-text text-2xl font-bold mt-2">{{ $card['value'] }}</p>
        </div>
      @endforeach
    </div>

    <div class="gs-card rounded-lg overflow-x-auto border-[#545878]">

      <table class="min-w-full text-sm text-left">

        {{-- HEADER --}}
        <thead class="bg-[#1C2035] border-b border-t border-[#545878]">
          <tr class="gs-secondary-text text-xs uppercase tracking-wider">
            <th class="px-4 py-3">#</th>
            <th class="px-4 py-3">Student Name</th>
            <th class="px-4 py-3">Section</th>
            <th class="px-4 py-3">GPA / Average</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Date Generated</th>
            <th class="px-4 py-3 text-center">Actions</th>
          </tr>
        </thead>

        {{-- BODY --}}
        <tbody class="divide-y divide-[#2E3350]">

          @forelse ($items as $index => $item)
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
                {{ $item['section'] }}
              </td>

              {{-- AVERAGE --}}
              <td class="px-4 py-3 gs-secondary-text font-semibold font-mono">
                {{ $item['average'] }}
              </td>

              {{-- STATUS --}}
              <td class="px-4 py-3">
                <span
                  class="px-2.5 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-wider
                        {{ $item['status'] === 'Available' ? 'gs-success-bg gs-success-text ' : 'bg-[#3B2437] text-red-400 border-red-900/40' }}">
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

                  <a href="?{{ http_build_query(array_merge(request()->query(), ['view_student' => $item['id']])) }}"
                    class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer text-xs transition">
                    <i data-lucide="eye" class="w-4 h-4"></i>
                    View Card
                  </a>

                  @if ($item['status'] === 'Available')
                    <a href="{{ route('grade-reports.pdf', $item['id']) }}"
                      class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer text-xs transition">
                      <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
                      Download PDF
                    </a>
                  @else
                    <button disabled
                      class="flex items-center gap-1 opacity-30 gs-secondary-text bg-[#22273D] px-2 py-1 rounded-lg border gs-primary-border-color cursor-not-allowed text-xs">
                      <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
                      Download PDF
                    </button>
                  @endif

                </div>
              </td>

            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-4 py-8 text-center gs-secondary-text">
                No students enrolled or matches found.
              </td>
            </tr>
          @endforelse

        </tbody>

      </table>
    </div>

    {{-- VIEW STUDENT GRADE REPORT CARD MODAL --}}
    @if ($viewStudent)
      <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/75 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="view-report-card-title">
        <div class="gs-card w-full max-w-2xl rounded-2xl shadow-2xl border border-[#545878]/40 bg-[#13162A] max-h-[90vh] overflow-y-auto flex flex-col">
          
          {{-- Modal Header --}}
          <div class="flex items-start justify-between gap-4 border-b border-[#545878]/30 px-6 py-5">
            <div>
              <h2 id="view-report-card-title" class="text-xl font-bold text-white flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5 text-[#8B84FF]"></i>
                Student Grade Report Card
              </h2>
              <p class="text-xs gs-secondary-text mt-1">Academic Year 2025 - 2026</p>
            </div>
            <a href="?{{ http_build_query(request()->except('view_student')) }}"
              class="rounded-lg p-2 text-[#545878] hover:bg-[#22273D] hover:text-white transition cursor-pointer"
              aria-label="Close">
              <i data-lucide="x" class="w-5 h-5"></i>
            </a>
          </div>

          {{-- Modal Body --}}
          <div class="px-6 py-5 space-y-6 flex-1">
            {{-- Student Details Grid --}}
            <div class="grid grid-cols-3 gap-4 bg-[#0D0F1A] p-4 rounded-xl border border-[#545878]/25">
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Student Name</p>
                <p class="text-sm font-semibold text-white tracking-wide truncate">{{ $viewStudent->full_name }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Grade & Section</p>
                <p class="text-sm font-semibold text-white truncate">Grade {{ $viewStudent->section?->year_level }} — {{ $viewStudent->section?->section }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Adviser</p>
                <p class="text-sm font-semibold text-white truncate">Mr. Erico Casil</p>
              </div>
            </div>

            {{-- Multi-Subject Performance Table --}}
            <div class="border border-[#545878]/30 rounded-xl overflow-hidden bg-[#0D0F1A]">
              <table class="min-w-full text-xs text-left">
                <thead class="bg-[#1C2035] border-b border-[#545878]/30">
                  <tr class="text-gray-400 uppercase tracking-wider font-semibold">
                    <th class="px-4 py-3">Subject</th>
                    @foreach ($quarters as $q)
                      <th class="px-3 py-3 text-center w-16">{{ $q }}</th>
                    @endforeach
                    <th class="px-4 py-3 text-center w-20">Final</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-[#2E3350]/40">
                  @forelse($reportCardRows as $r)
                    <tr class="hover:bg-[#22273D]/30 transition">
                      <td class="px-4 py-3 text-white font-medium">{{ $r['subject'] }}</td>
                      @foreach ($quarters as $q)
                        <td class="px-3 py-3 text-center text-gray-300 font-mono">
                          {{ $r['grades'][$q] !== null ? number_format($r['grades'][$q], 0) : '—' }}
                        </td>
                      @endforeach
                      <td class="px-4 py-3 text-center text-white font-bold font-mono">
                        {{ $r['average'] !== null ? number_format($r['average'], 0) : '—' }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="{{ 2 + count($quarters) }}" class="px-4 py-6 text-center text-gray-500">
                        No grading records found for this student.
                      </td>
                    </tr>
                  @endforelse

                  {{-- Average Row (Vertical Column Averages) --}}
                  @if ($reportCardRows !== [])
                    @php
                      $colAverages = [];
                      foreach ($quarters as $quarter) {
                          $scores = [];
                          foreach ($reportCardRows as $row) {
                              if (isset($row['grades'][$quarter]) && $row['grades'][$quarter] !== null) {
                                  $scores[] = (float)$row['grades'][$quarter];
                              }
                          }
                          $colAverages[$quarter] = $scores === [] ? null : round(array_sum($scores) / count($scores), 0);
                      }
                    @endphp
                    <tr class="bg-[#1C2035]/30 font-bold border-t border-[#545878]/30">
                      <td class="px-4 py-3 text-gray-300">Average</td>
                      @foreach($quarters as $q)
                        <td class="px-3 py-3 text-center text-gray-300 font-mono">
                          {{ $colAverages[$q] !== null ? $colAverages[$q] : '—' }}
                        </td>
                      @endforeach
                      <td class="px-4 py-3 text-center text-gray-500 font-mono">—</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>

            {{-- Summary GPA & Status Badge --}}
            <div class="flex items-center justify-between bg-[#1E1F44]/40 border border-[#31326E]/60 rounded-xl px-5 py-4">
              <div class="flex items-center gap-3">
                <div>
                  <h4 class="text-white text-sm font-semibold">General Point Average (GPA)</h4>
                  <p class="text-[10px] text-gray-400 mt-0.5">Calculated based on subjects average performance</p>
                </div>
              </div>

              <div class="flex items-center gap-3.5">
                <p class="text-[#8B84FF] text-2xl font-bold font-mono">
                  GPA: {{ $viewGpa !== null ? number_format($viewGpa, 0) : '—' }}
                </p>
                <div>
                  @if($viewGpaRemarks === 'Passed')
                    <span class="px-3 py-1 rounded text-xs bg-[#22C55E]/15 text-[#22C55E] border border-[#22C55E]/20 font-bold font-mono">Passed</span>
                  @elseif($viewGpaRemarks === 'Failed')
                    <span class="px-3 py-1 rounded text-xs bg-[#EF4444]/15 text-[#EF4444] border border-[#EF4444]/20 font-bold font-mono">Failed</span>
                  @else
                    <span class="px-3 py-1 rounded text-xs bg-[#22273D] text-gray-500 border border-[#545878]/30 font-bold">—</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Modal Footer --}}
          <div class="flex items-center justify-between border-t border-[#545878]/30 px-6 py-4 bg-[#0D0F1A]/50">
            @php
              $hasApprovedReport = \App\Models\GradeReport::where('student_id', $viewStudent->id)->exists();
            @endphp
            @if ($hasApprovedReport)
              <span class="text-xs text-[#22C55E] bg-[#22C55E]/10 px-3 py-2 rounded-lg border border-[#22C55E]/20 flex items-center gap-1">
                <i data-lucide="check-circle" class="w-4 h-4"></i>
                Report Card Signed & Approved
              </span>
            @elseif ($viewGpa !== null)
              <form method="POST" action="{{ route('grade-reports.approve', $viewStudent->id) }}" class="m-0">
                @csrf
                <button type="submit"
                  class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold py-2.5 px-5 rounded-lg flex items-center gap-1.5 cursor-pointer transition">
                  <i data-lucide="check-square" class="w-4 h-4"></i>
                  Approve Report Card
                </button>
              </form>
            @else
              <span class="text-xs text-gray-400 bg-gray-400/5 px-3 py-2 rounded-lg border border-[#545878]/30 flex items-center gap-1">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                Grades must be recorded before approval
              </span>
            @endif

            <a href="?{{ http_build_query(request()->except('view_student')) }}"
              class="gs-secondary-btn text-xs py-2 px-5 inline-flex items-center justify-center cursor-pointer transition">
              Close Preview
            </a>
          </div>
        </div>
      </div>
    @endif

  </div>

@endsection
