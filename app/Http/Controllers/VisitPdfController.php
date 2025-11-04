<?php

namespace App\Http\Controllers;

use App\Helpers\PdfHelper;
use App\Models\Visit;
use Spatie\LaravelPdf\Facades\Pdf;

class VisitPdfController extends Controller
{
    /**
     * Generate and download PDF report for a visit.
     */
    public function generatePdf(Visit $visit)
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        // Eager load all necessary relationships
        $visit->load([
            'patient',
            'doctor',
            'anamnesis',
            'ophthalmicExam.refractions',
            'imagingStudies',
            'treatmentPlans',
            'prescriptions.prescriptionItems',
            'spectaclePrescriptions',
            'diagnoses',
        ]);

        $filename = sprintf(
            'visit-report-%s-%s.pdf',
            $visit->patient->last_name,
            $visit->scheduled_at->format('Y-m-d')
        );

        return Pdf::view('pdf.visit-report', ['visit' => $visit])
            ->format('a4')
            ->name($filename)
            ->withBrowsershot(fn ($browsershot) => PdfHelper::configureBrowsershot($browsershot));
    }
}
