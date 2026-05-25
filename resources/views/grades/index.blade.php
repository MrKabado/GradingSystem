@extends('layouts.index')

@section('content')
  <div class="gs-main-page">
    @if (session('status'))
      <div class="rounded-lg border border-[#31326E] bg-[#1E1F44] px-4 py-3 text-sm text-[#8B84FF]">
        {{ session('status') }}
      </div>
    @endif

    {{-- Title & Dynamic S.Y. Badge --}}
    <div class="gs-page-header sm:items-end">
      <div class="min-w-0">
        <h1 class="gs-page-title gs-primary-text">Grades</h1>
        <p class="gs-page-subtitle">Manage and monitor all students grades</p>
      </div>

      @if($activeSection)
        <span class="w-fit text-xs text-[#8B84FF] font-semibold bg-[#1E1F44] border border-[#31326E] px-3.5 py-1.5 rounded-lg shrink-0">
          S.Y. 2025 - 2026 — 4th Quarter
        </span>
      @endif
    </div>

    <x-section-filters
      :action="route('grades.index')"
      :reset-url="route('grades.index')"
      :year-levels="$yearLevels"
      :section-names="$sectionNames"
      :selected-year-level="$selectedYearLevel"
      :selected-section="$selectedSection"
      search-placeholder="Search by name or ID..."
    />

    {{-- Main Performance Dashboard --}}
    @if ($activeSection)
      <div class="space-y-6">
        {{-- CARDS FOR TOTAL STUDENT, PASSED, FAILED, CLASS AVERAGE --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="gs-card rounded-xl p-4 flex flex-col justify-between space-y-2">
            <div class="flex items-center justify-between text-gray-400">
              <span class="text-xs font-semibold uppercase tracking-wider">Total Students</span>
              <i data-lucide="users" class="w-4 h-4"></i>
            </div>
            <p class="gs-primary-text text-3xl font-semibold">{{ $stats['total'] }}</p>
          </div>

          <div class="gs-card rounded-xl p-4 flex flex-col justify-between space-y-2">
            <div class="flex items-center justify-between text-[#22C55E]">
              <span class="text-xs font-semibold uppercase tracking-wider">Passed</span>
              <i data-lucide="badge-check" class="w-4 h-4"></i>
            </div>
            <p class="text-[#22C55E] text-3xl font-semibold">{{ $stats['passed'] }}</p>
          </div>

          <div class="gs-card rounded-xl p-4 flex flex-col justify-between space-y-2">
            <div class="flex items-center justify-between text-[#EF4444]">
              <span class="text-xs font-semibold uppercase tracking-wider">Failed</span>
              <i data-lucide="badge-alert" class="w-4 h-4"></i>
            </div>
            <p class="text-[#EF4444] text-3xl font-semibold">{{ $stats['failed'] }}</p>
          </div>

          <div class="gs-card rounded-xl p-4 flex flex-col justify-between space-y-2">
            <div class="flex items-center justify-between text-[#8B84FF]">
              <span class="text-xs font-semibold uppercase tracking-wider">Class Average</span>
              <i data-lucide="award" class="w-4 h-4"></i>
            </div>
            <p class="text-[#8B84FF] text-3xl font-semibold">
              {{ $stats['average'] !== null ? $stats['average'] . '%' : '—' }}
            </p>
          </div>
        </div>

        {{-- Students Performance List Table --}}
        <div class="gs-card rounded-xl py-4 space-y-4">
          <div class="gs-card-header px-4 sm:px-5">
            <div class="min-w-0">
              <h3 class="text-base sm:text-lg font-semibold text-gray-300">Student Performance Record</h3>
              <p class="text-xs gs-secondary-text mt-1">Displays student quarterly averages across all offered subjects</p>
            </div>
            <div class="w-fit text-[#8B84FF] bg-[#1E1F44] px-3.5 py-1.5 rounded-lg border border-[#31326E] text-xs font-semibold shrink-0">
              Grade {{ $activeSection->year_level }} — {{ $activeSection->section }}
            </div>
          </div>

          <div class="gs-table-wrap border-t border-[#545878]/30">
            <table class="min-w-[720px] w-full text-sm text-left">
              <thead class="bg-[#1C2035] border-b border-[#545878]/30">
                <tr class="text-gray-400 text-xs uppercase tracking-wider font-semibold">
                  <th class="px-5 py-4 w-16">#</th>
                  <th class="px-5 py-4">Student Name</th>
                  @foreach ($quarters as $q)
                    <th class="px-5 py-4 text-center">{{ $q }}</th>
                  @endforeach
                  <th class="px-5 py-4 text-center">Average</th>
                  <th class="px-5 py-4 text-center">Remarks</th>
                  <th class="px-5 py-4 text-center">Actions</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-[#2E3350]/60">
                @forelse ($gradeRows as $index => $row)
                  <tr class="hover:bg-[#22273D]/50 transition duration-150">
                    <td class="px-5 py-4 text-gray-400 font-mono">{{ sprintf('%02d', $index + 1) }}</td>
                    <td class="px-5 py-4 gs-primary-text font-medium tracking-wide">{{ $row['name'] }}</td>
                    @foreach ($quarters as $q)
                      <td class="px-5 py-4 text-center text-gray-300 font-mono">
                        {{ $row['grades'][$q] !== null ? number_format($row['grades'][$q], 0) : '—' }}
                      </td>
                    @endforeach
                    <td class="px-5 py-4 text-center text-gray-300 font-semibold font-mono">
                      {{ $row['average'] !== null ? number_format($row['average'], 1) : '—' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                      @if ($row['remarks'] === 'Passed')
                        <span class="px-2.5 py-1 rounded-md text-xs gs-success-bg gs-success-text">
                          Passed
                        </span>
                      @elseif ($row['remarks'] === 'Failed')
                        <span class="px-2.5 py-1 rounded-md text-xs gs-failed-bg gs-failed-text">
                          Failed
                        </span>
                      @else
                        <span class="gs-secondary-text font-mono">—</span>
                      @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                      <div class="flex items-center justify-center gap-2">
                        {{-- View Report card --}}
                        <a href="{{ route('grades.show', ['student' => $row['student_id'], 'year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
                          class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                          <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                          View
                        </a>

                        {{-- Edit Report Card --}}
                        <a href="{{ route('grades.edit', ['student' => $row['student_id'], 'year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
                          class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                          <i data-lucide="square-pen" class="w-3.5 h-3.5"></i>
                          Edit
                        </a>

                        {{-- Clear Grades --}}
                        <form method="POST" action="{{ route('grades.destroy', $row['student_id']) }}" class="inline m-0"
                              onsubmit="return confirm('Clear grades of {{ $row['name'] }} across all subjects in this section?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="flex items-center gap-1 gs-secondary-text bg-[#22273D] hover:bg-[#2B304A] px-2 py-1 rounded-lg border gs-primary-border-color cursor-pointer">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                            Delete
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="{{ 5 + count($quarters) }}" class="px-5 py-10 text-center gs-secondary-text">
                      @if ($subjects->isEmpty())
                        No subjects offered in Grade {{ $selectedYearLevel }} - Section {{ $selectedSection }} yet. Add subjects to begin grading.
                      @else
                        No students enrolled in Grade {{ $selectedYearLevel }} - Section {{ $selectedSection }} match your search.
                      @endif
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    @else
      <div class="gs-card rounded-xl p-16 text-center space-y-3 border border-[#545878]/30">
        <i data-lucide="layers" class="w-16 h-16 mx-auto text-[#545878]/80"></i>
        <h3 class="text-lg font-semibold text-gray-200">Select Section & Year Level</h3>
        <p class="text-sm gs-secondary-text max-w-lg mx-auto">Please select a year level and section from the dropdowns above to view curriculum subjects and manage students performance records.</p>
      </div>
    @endif


    {{-- MODALS SECTION --}}

    {{-- 1. VIEW STUDENT GRADE REPORT CARD MODAL --}}
    @if ($modalMode === 'view' && $gradeFormStudent)
      <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/75 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="view-report-card-title">
        <div class="gs-card w-full sm:max-w-2xl rounded-t-2xl sm:rounded-2xl shadow-2xl border border-[#545878]/40 bg-[#13162A] max-h-[92vh] sm:max-h-[90vh] overflow-y-auto flex flex-col">
          
          {{-- Modal Header --}}
          <div class="flex items-start justify-between gap-4 border-b border-[#545878]/30 px-6 py-5">
            <div>
              <h2 id="view-report-card-title" class="text-xl font-bold gs-primary-text flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5 text-[#8B84FF]"></i>
                Student Grade Report
              </h2>
              <p class="text-xs gs-secondary-text mt-1">Academic Year 2025 - 2026</p>
            </div>
            <a href="{{ route('grades.index', ['year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
              class="rounded-lg p-2 text-[#545878] hover:bg-[#22273D] hover:gs-primary-text transition cursor-pointer"
              aria-label="Close">
              <i data-lucide="x" class="w-5 h-5"></i>
            </a>
          </div>

          {{-- Modal Body --}}
          <div class="px-6 py-5 space-y-6 flex-1">
            {{-- Student Details Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-[#0D0F1A] p-4 rounded-xl border border-[#545878]/25">
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Student Name</p>
                <p class="text-sm font-semibold gs-primary-text tracking-wide truncate">{{ $gradeFormStudent->full_name }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Grade & Section</p>
                <p class="text-sm font-semibold gs-primary-text truncate">Grade {{ $gradeFormStudent->section?->year_level }} — {{ $gradeFormStudent->section?->section }}</p>
              </div>
              <div class="space-y-1">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Adviser</p>
                <p class="text-sm font-semibold gs-primary-text truncate">{{ $gradeFormStudent->section?->class_adviser ?? '—' }}</p>
              </div>
            </div>

            {{-- Multi-Subject Performance Table --}}
            <div class="border border-[#545878]/30 rounded-xl overflow-hidden bg-[#0D0F1A] overflow-x-auto">
              <table class="min-w-[480px] w-full text-xs text-left">
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
                      <td class="px-4 py-3 gs-primary-text font-medium">{{ $r['subject'] }}</td>
                      @foreach ($quarters as $q)
                        <td class="px-3 py-3 text-center text-gray-300 font-mono">
                          {{ $r['grades'][$q] !== null ? number_format($r['grades'][$q], 0) : '—' }}
                        </td>
                      @endforeach
                      <td class="px-4 py-3 text-center gs-primary-text font-bold font-mono">
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
                  <h4 class="gs-primary-text text-sm font-semibold">General Point Average (GPA)</h4>
                  <p class="text-[10px] text-gray-400 mt-0.5">Calculated based on subjects average performance</p>
                </div>
              </div>

              <div class="flex items-center gap-3.5">
                <p class="text-[#8B84FF] text-2xl font-bold font-mono">
                  GPA: {{ $gpa !== null ? number_format($gpa, 0) : '—' }}
                </p>
                <div>
                  @if($gpaRemarks === 'Passed')
                    <span class="px-3 py-1 rounded text-xs bg-[#22C55E]/15 text-[#22C55E] border border-[#22C55E]/20 font-bold">Passed</span>
                  @elseif($gpaRemarks === 'Failed')
                    <span class="px-3 py-1 rounded text-xs bg-[#EF4444]/15 text-[#EF4444] border border-[#EF4444]/20 font-bold">Failed</span>
                  @else
                    <span class="px-3 py-1 rounded text-xs bg-[#22273D] text-gray-500 border border-[#545878]/30 font-bold">—</span>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- Modal Footer --}}
          <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-3 border-t border-[#545878]/30 px-4 sm:px-6 py-4 bg-[#0D0F1A]/50">
            <button onclick="alert('Report card successfully signed & approved.')"
              class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 gs-primary-text text-xs font-semibold py-2.5 px-5 rounded-lg flex items-center gap-1.5 cursor-pointer transition">
              <i data-lucide="check-square" class="w-4 h-4"></i>
              Approve Report Card
            </button>

            <a href="{{ route('grades.index', ['year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
              class="w-full sm:w-auto justify-center gs-secondary-btn text-xs py-2 px-5 inline-flex items-center cursor-pointer transition">
              Close Report
            </a>
          </div>
        </div>
      </div>
    @endif


    {{-- 2. DYNAMIC LIVE CALCULATING MULTI-SUBJECT GRADE EDITOR MODAL --}}
    @if ($modalMode === 'edit' && $gradeFormStudent && $reportCardRows !== [])
      <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 bg-black/75 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="edit-grade-title">
        <div class="gs-card w-full sm:max-w-3xl rounded-t-2xl sm:rounded-2xl shadow-2xl border border-[#545878]/40 bg-[#13162A] max-h-[92vh] sm:max-h-[90vh] overflow-y-auto flex flex-col">
          
          {{-- Header --}}
          <div class="flex items-start justify-between gap-4 border-b border-[#545878]/30 px-6 py-4">
            <div>
              <h2 id="edit-grade-title" class="text-lg font-bold gs-primary-text flex items-center gap-2">
                <i data-lucide="square-pen" class="w-5 h-5 text-indigo-400"></i>
                Grade Editor
              </h2>
              <p class="text-xs gs-secondary-text mt-1">Add or update student quarterly scores for all offered subjects</p>
            </div>
            <a href="{{ route('grades.index', ['year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
              class="rounded-lg p-2 text-[#545878] hover:bg-[#22273D] hover:text-white transition cursor-pointer" aria-label="Close">
              <i data-lucide="x" class="w-5 h-5"></i>
            </a>
          </div>

          {{-- Form --}}
          <form method="POST" action="{{ route('grades.update', $gradeFormStudent->id) }}" class="flex flex-col m-0" id="multi-grade-edit-form">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="student_id" value="{{ $gradeFormStudent->id }}">

            {{-- Form Content --}}
            <div class="px-6 py-5 space-y-5 flex-1">
              {{-- Context Header Card --}}
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 bg-[#0D0F1A] p-4 rounded-xl border border-[#545878]/25">
                <div>
                  <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Student Name</p>
                  <p class="text-sm font-semibold text-white mt-0.5 truncate">{{ $gradeFormStudent->full_name }}</p>
                </div>
                <div>
                  <p class="text-[9px] font-bold text-gray-500 uppercase tracking-wider">Grade & Section</p>
                  <p class="text-sm font-semibold text-indigo-400 mt-0.5 truncate">Grade {{ $gradeFormStudent->section?->year_level }} — {{ $gradeFormStudent->section?->section }}</p>
                </div>
              </div>

              {{-- Multi-Subject Editable Table --}}
              <div class="border border-[#545878]/30 rounded-xl overflow-hidden bg-[#0D0F1A] overflow-x-auto">
                <table class="min-w-[520px] w-full text-xs text-left">
                  <thead class="bg-[#1C2035] border-b border-[#545878]/30">
                    <tr class="text-gray-400 uppercase tracking-wider font-semibold">
                      <th class="px-4 py-3">Subject</th>
                      @foreach ($quarters as $q)
                        <th class="px-3 py-3 text-center w-24">{{ $q }}</th>
                      @endforeach
                      <th class="px-4 py-3 text-center w-20">Final</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-[#2E3350]/40">
                    @foreach($reportCardRows as $r)
                      <tr class="subject-row hover:bg-[#22273D]/30 transition" data-subject-id="{{ $r['subject_id'] }}">
                        <td class="px-4 py-3.5 text-white font-medium">{{ $r['subject'] }}</td>
                        @foreach ($quarters as $q)
                          <td class="px-3 py-1.5 text-center">
                            <input 
                              type="number" 
                              name="grades[{{ $r['subject_id'] }}][{{ $q }}]" 
                              value="{{ $r['grades'][$q] !== null ? $r['grades'][$q] : '' }}" 
                              min="0" max="100" step="1"
                              placeholder="—"
                              class="quarter-input input-{{ strtolower($q) }} w-16 text-center rounded-lg border border-[#545878]/60 bg-[#13162A] px-2 py-1 text-sm text-white font-mono focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition duration-150" />
                          </td>
                        @endforeach
                        <td class="px-4 py-3.5 text-center text-white font-bold font-mono subject-final">—</td>
                      </tr>
                    @endforeach

                    {{-- Dynamic Averages Row --}}
                    <tr class="bg-[#1C2035]/30 font-bold border-t border-[#545878]/30">
                      <td class="px-4 py-3 text-gray-300">Average</td>
                      <td id="avg-q1" class="px-3 py-3 text-center text-gray-300 font-mono font-semibold">—</td>
                      <td id="avg-q2" class="px-3 py-3 text-center text-gray-300 font-mono font-semibold">—</td>
                      <td id="avg-q3" class="px-3 py-3 text-center text-gray-300 font-mono font-semibold">—</td>
                      <td id="avg-q4" class="px-3 py-3 text-center text-gray-300 font-mono font-semibold">—</td>
                      <td class="px-4 py-3 text-center text-gray-500 font-mono">—</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            {{-- Footer / Live Calculator Actions --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between border-t border-[#545878]/30 px-4 sm:px-6 py-4 bg-[#0D0F1A]/50">
              <div class="flex flex-wrap items-center gap-3">
                <span id="bottom-remarks" class="px-3 py-1 rounded text-xs font-bold bg-[#22273D] text-gray-500 border border-[#545878]/30">—</span>
                <span id="bottom-gpa" class="text-white text-md font-bold font-mono">GPA: —</span>
              </div>

              <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5">
                <a href="{{ route('grades.index', ['year_level' => $selectedYearLevel, 'section' => $selectedSection]) }}"
                  class="w-full sm:w-auto justify-center gs-secondary-btn text-xs py-2.5 px-5 inline-flex items-center cursor-pointer transition">
                  Cancel
                </a>
                <button type="submit"
                  class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-6 rounded-lg cursor-pointer transition shadow-lg">
                  Save Grades
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- Two-Dimensional Reactive JS Engine --}}
      <script>
        document.addEventListener('DOMContentLoaded', function() {
          const editModal = document.getElementById('multi-grade-edit-form');
          if (!editModal) return;

          const rows = editModal.querySelectorAll('.subject-row');
          const avgCells = {
            q1: editModal.querySelector('#avg-q1'),
            q2: editModal.querySelector('#avg-q2'),
            q3: editModal.querySelector('#avg-q3'),
            q4: editModal.querySelector('#avg-q4'),
          };
          const bottomGpaEl = editModal.querySelector('#bottom-gpa');
          const bottomRemarksEl = editModal.querySelector('#bottom-remarks');

          function calculateGrades() {
            let verticalSums = { q1: 0, q2: 0, q3: 0, q4: 0 };
            let verticalCounts = { q1: 0, q2: 0, q3: 0, q4: 0 };
            let subjectFinals = [];

            rows.forEach(row => {
              let subjectSum = 0;
              let subjectCount = 0;

              ['q1', 'q2', 'q3', 'q4'].forEach(qKey => {
                const input = row.querySelector('.input-' + qKey);
                if (input && input.value !== '') {
                  const val = parseFloat(input.value);
                  if (!isNaN(val) && val >= 0 && val <= 100) {
                    subjectSum += val;
                    subjectCount++;
                    
                    verticalSums[qKey] += val;
                    verticalCounts[qKey]++;
                  }
                }
              });

              const finalCell = row.querySelector('.subject-final');
              if (subjectCount > 0) {
                const subAvg = subjectSum / subjectCount;
                finalCell.textContent = Math.round(subAvg);
                subjectFinals.push(subAvg);
              } else {
                finalCell.textContent = '—';
              }
            });

            // Update vertical quarterly averages
            ['q1', 'q2', 'q3', 'q4'].forEach(qKey => {
              const cell = avgCells[qKey];
              if (verticalCounts[qKey] > 0) {
                const qAvg = verticalSums[qKey] / verticalCounts[qKey];
                cell.textContent = Math.round(qAvg);
              } else {
                cell.textContent = '—';
              }
            });

            // Update overall GPA
            if (subjectFinals.length > 0) {
              const gpa = subjectFinals.reduce((a, b) => a + b, 0) / subjectFinals.length;
              bottomGpaEl.textContent = 'GPA: ' + Math.round(gpa);
              
              if (gpa >= 75) {
                bottomRemarksEl.className = 'px-3 py-1 rounded text-xs bg-[#22C55E]/15 text-[#22C55E] border border-[#22C55E]/20 font-bold';
                bottomRemarksEl.textContent = 'Passed';
              } else {
                bottomRemarksEl.className = 'px-3 py-1 rounded text-xs bg-[#EF4444]/15 text-[#EF4444] border border-[#EF4444]/20 font-bold';
                bottomRemarksEl.textContent = 'Failed';
              }
            } else {
              bottomGpaEl.textContent = 'GPA: —';
              bottomRemarksEl.className = 'px-3 py-1 rounded text-xs bg-[#22273D] text-gray-500 border border-[#545878]/30 font-bold';
              bottomRemarksEl.textContent = '—';
            }
          }

          // Attach event listeners
          editModal.querySelectorAll('.quarter-input').forEach(input => {
            input.addEventListener('input', calculateGrades);
            input.addEventListener('change', calculateGrades);
          });

          // Initialize calculations
          calculateGrades();
        });
      </script>
    @endif
  </div>
@endsection