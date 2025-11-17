<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Support\Facades\Http;

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

        $visitArray = $visit->toArray();

        $response = Http::post('http://host.docker.internal:3000/api/pdf/generate', [
            'template_specifications' => [
                "folder" => "reports",
                "id" => "anamnesis",
                "locale" => "mk"
            ],
            "data" => $visitArray
        ]);

        $base64 = $response['data']['base64'];
        $pdf = base64_decode($base64);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="visit-report.pdf"',
        ]);

    }
}
