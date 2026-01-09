<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Support\Facades\Http;
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

        return Pdf::view('pdf.visit-report', ['visit' => $visit])
        ->format('a4')
        ->download('visit-report.pdf');


    }
}
