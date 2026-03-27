<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    /**
     * Show prescriptions page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'prescriptions.doctor', 'prescriptions.prescriptionItems']);

        return view('visits.workspace.prescriptions', compact('visit'));
    }

    /**
     * Show create prescription form.
     */
    public function create(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.prescription-create', compact('visit'));
    }

    /**
     * Show edit prescription form.
     */
    public function edit(Visit $visit, Prescription $prescription): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);
        $prescription->load('prescriptionItems');

        return view('visits.workspace.prescription-edit', compact('visit', 'prescription'));
    }

    /**
     * Delete a prescription.
     */
    public function destroy(Visit $visit, Prescription $prescription): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $prescription->delete(); // This will also delete related items due to cascade

        return redirect()->route('visits.prescriptions', $visit)->with('success', 'Prescription deleted successfully.');
    }
}
