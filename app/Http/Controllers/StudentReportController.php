<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;

class StudentReportController extends Controller
{
    public function pdfDownload($id) {
        $student = Student::with('grades')->findOrFail($id);

        $pdf = Pdf::loadView('pdf.report-card', compact('student'))
                ->setPaper('a4', 'potrait')
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                ]);

        return $pdf->download($student->student_id . '-report-card.pdf');
    }

    public function approve($id) {
        $student = Student::findOrFail($id);

        \App\Models\GradeReport::updateOrCreate([
            'student_id' => $student->id,
        ], [
            'approved_at' => now(),
        ]);

        \App\Models\ActivityLog::record(
            'approved',
            'grade_reports',
            $student->id,
            'Approved grade report card for ' . $student->full_name
        );

        return redirect()->back()->with('status', 'Report card for ' . $student->full_name . ' has been approved successfully.');
    }
}
