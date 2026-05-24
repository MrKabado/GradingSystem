<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Card - {{ $student->full_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 13px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #6366F1;
            padding-bottom: 15px;
        }
        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .school-subtitle {
            font-size: 11px;
            color: #6b7280;
            margin-top: 5px;
        }
        .report-title {
            font-size: 15px;
            font-weight: bold;
            color: #4f46e5;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }
        .student-info {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .student-info td {
            padding: 6px 10px;
            vertical-align: top;
        }
        .info-label {
            font-weight: bold;
            color: #4b5563;
            width: 18%;
        }
        .info-value {
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .grades-table th {
            background-color: #1e1b4b;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 10px;
            text-align: left;
        }
        .grades-table th.center, .grades-table td.center {
            text-align: center;
        }
        .grades-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
        }
        .grades-table tr:nth-child(even) td {
            background-color: #f9fafb;
        }
        .subject-name {
            font-weight: bold;
            color: #111827;
        }
        .gpa-card {
            background-color: #f5f3ff;
            border: 1px solid #ddd6fe;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 35px;
        }
        .gpa-title {
            font-size: 12px;
            font-weight: bold;
            color: #5b21b6;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .gpa-value {
            font-size: 24px;
            font-weight: bold;
            color: #4c1d95;
        }
        .remarks-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .remarks-passed {
            background-color: #dcfce7;
            color: #15803d;
        }
        .remarks-failed {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .signatures {
            width: 100%;
            margin-top: 50px;
        }
        .signatures td {
            text-align: center;
            width: 50%;
        }
        .sig-line {
            width: 200px;
            border-bottom: 1px solid #4b5563;
            margin: 0 auto 5px auto;
        }
        .sig-label {
            font-size: 11px;
            color: #6b7280;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="school-name">Mister Kabado Academy</div>
        <div class="school-subtitle">Poblacion Ward II, Minglanilla, Cebu, Philippines | Phone: (+63) 991-533-7918</div>
        <div class="report-title">OFFICIAL STUDENT REPORT CARD</div>
        <div class="school-subtitle">Academic Year 2025 - 2026</div>
    </div>

    <table class="student-info">
        <tr>
            <td class="info-label">Student Name:</td>
            <td class="info-value" style="font-weight: bold;">{{ $student->full_name }}</td>
            <td class="info-label" style="padding-left: 20px;">Student ID:</td>
            <td class="info-value" style="font-family: monospace;">{{ $student->student_id }}</td>
        </tr>
        <tr>
            <td class="info-label">Grade & Sec:</td>
            <td class="info-value">{{ $student->section ? 'Grade ' . $student->section->year_level . ' - ' . $student->section->section : '—' }}</td>
            <td class="info-label" style="padding-left: 20px;">Adviser:</td>
            <td class="info-value">Mr. Erico Casil</td>
        </tr> 
    </table>

    @php
        $quarters = ['Q1', 'Q2', 'Q3', 'Q4'];
        $reportCardRows = [];
        $totalFinalGrades = [];

        if ($student->section_id) {
            $sectionSubjects = \App\Models\Subject::where('section_id', $student->section_id)
                ->with('teacher')
                ->orderBy('name')
                ->get();

            $studentAllGrades = \App\Models\Grade::where('student_id', $student->id)
                ->get()
                ->groupBy('subject_id');

            foreach ($sectionSubjects as $sub) {
                $subGrades = $studentAllGrades->get($sub->id, collect());
                $qGrades = [];
                foreach ($quarters as $quarter) {
                    $rec = $subGrades->firstWhere('quarter', $quarter);
                    $qGrades[$quarter] = $rec?->grade !== null ? (float) $rec->grade : null;
                }
                $filledQ = array_filter($qGrades, fn($v) => $v !== null);
                $subAvg = $filledQ === [] ? null : round(array_sum($filledQ) / count($filledQ), 1);
                if ($subAvg !== null) {
                    $totalFinalGrades[] = $subAvg;
                }
                $reportCardRows[] = [
                    'subject' => $sub->name,
                    'grades' => $qGrades,
                    'average' => $subAvg,
                    'remarks' => $subAvg === null ? '—' : ($subAvg >= \App\Models\Grade::PASSING_SCORE ? 'Passed' : 'Failed'),
                ];
            }
        }

        $viewGpa = $totalFinalGrades === [] ? null : round(array_sum($totalFinalGrades) / count($totalFinalGrades), 1);
        $viewGpaRemarks = $viewGpa === null ? null : ($viewGpa >= \App\Models\Grade::PASSING_SCORE ? 'Passed' : 'Failed');
    @endphp

    <table class="grades-table">
        <thead>
            <tr>
                <th style="width: 40%;">Learning Areas (Subjects)</th>
                @foreach ($quarters as $q)
                    <th class="center" style="width: 10%;">{{ $q }}</th>
                @endforeach
                <th class="center" style="width: 10%;">Final</th>
                <th class="center" style="width: 10%;">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportCardRows as $r)
                <tr>
                    <td class="subject-name">{{ $r['subject'] }}</td>
                    @foreach ($quarters as $q)
                        <td class="center">{{ $r['grades'][$q] !== null ? number_format($r['grades'][$q], 0) : '—' }}</td>
                    @endforeach
                    <td class="center" style="font-weight: bold;">{{ $r['average'] !== null ? number_format($r['average'], 0) : '—' }}</td>
                    <td class="center">
                        @if ($r['remarks'] === 'Passed')
                            <span class="remarks-badge remarks-passed">Passed</span>
                        @elseif ($r['remarks'] === 'Failed')
                            <span class="remarks-badge remarks-failed">Failed</span>
                        @else
                            <span>—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center" style="color: #6b7280; padding: 20px;">
                        No grading records found for this student.
                    </td>
                </tr>
            @endforelse

            {{-- Column Averages Row --}}
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
                <tr style="background-color: #f3f4f6; font-weight: bold;">
                    <td>Quarterly General Average</td>
                    @foreach($quarters as $q)
                        <td class="center">{{ $colAverages[$q] !== null ? $colAverages[$q] : '—' }}</td>
                    @endforeach
                    <td colspan="2"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <table style="width: 100%;">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <div class="gpa-card">
                    <div class="gpa-title">General Point Average (GPA)</div>
                    <div class="gpa-value">
                        {{ $viewGpa !== null ? number_format($viewGpa, 1) : '—' }}%
                        @if($viewGpaRemarks === 'Passed')
                            <span class="remarks-badge remarks-passed" style="font-size: 12px; margin-left: 10px; vertical-align: middle;">PASSED</span>
                        @elseif($viewGpaRemarks === 'Failed')
                            <span class="remarks-badge remarks-failed" style="font-size: 12px; margin-left: 10px; vertical-align: middle;">FAILED</span>
                        @endif
                    </div>
                </div>
            </td>
            <td style="width: 40%; vertical-align: top; padding-left: 20px;">
                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; font-size: 11px;">
                    <div style="font-weight: bold; margin-bottom: 5px; color: #374151;">Grading Scale:</div>
                    <table style="width: 100%;">
                        <tr><td>90 - 100</td><td>Outstanding</td></tr>
                        <tr><td>85 - 89</td><td>Very Satisfactory</td></tr>
                        <tr><td>80 - 84</td><td>Satisfactory</td></tr>
                        <tr><td>75 - 79</td><td>Fairly Satisfactory</td></tr>
                        <tr><td>Below 75</td><td style="color: #b91c1c;">Did Not Meet Expectations</td></tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table class="signatures">
        <tr>
            <td>
                <div class="sig-line"></div>
                <div style="font-weight: bold;">MR. ERICO CASIL</div>
                <div class="sig-label">Class Adviser</div>
            </td>
            <td>
                <div class="sig-line"></div>
                <div style="font-weight: bold;">MR. MISTER KABADO</div>
                <div class="sig-label">School Principal</div>
            </td>
        </tr>
    </table>

</body>
</html>
