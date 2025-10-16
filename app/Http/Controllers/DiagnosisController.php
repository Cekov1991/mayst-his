<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiagnosisRequest;
use App\Http\Requests\UpdateDiagnosisRequest;
use App\Models\Diagnosis;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiagnosisController extends Controller
{
    /**
     * Display diagnoses for a visit.
     */
    public function index(Visit $visit): View
    {
        $visit->load(['patient', 'diagnoses' => function ($query) {
            $query->with('diagnosedBy');
        }]);

        return view('visits.workspace.diagnoses', compact('visit'));
    }

    /**
     * Show the form for creating a new diagnosis.
     */
    public function create(Visit $visit): View
    {
        $visit->load('patient');

        $doctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('visits.workspace.diagnosis-create', compact('visit', 'doctors'));
    }

    /**
     * Store a newly created diagnosis.
     */
    public function store(StoreDiagnosisRequest $request, Visit $visit): RedirectResponse
    {
        $data = $request->validated();
        $data['visit_id'] = $visit->id;
        $data['patient_id'] = $visit->patient_id;

        // If this is marked as primary, unset any other primary diagnoses for this visit
        if ($data['is_primary'] ?? false) {
            $visit->diagnoses()->update(['is_primary' => false]);
        }

        $visit->diagnoses()->create($data);

        return redirect()->route('visits.diagnoses', $visit)->with('success', 'Diagnosis created successfully.');
    }

    /**
     * Show the form for editing a diagnosis.
     */
    public function edit(Visit $visit, Diagnosis $diagnosis): View
    {
        $visit->load('patient');

        $doctors = User::role('doctor')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('visits.workspace.diagnosis-edit', compact('visit', 'diagnosis', 'doctors'));
    }

    /**
     * Update the specified diagnosis.
     */
    public function update(UpdateDiagnosisRequest $request, Visit $visit, Diagnosis $diagnosis): RedirectResponse
    {
        $data = $request->validated();

        // If this is marked as primary, unset any other primary diagnoses for this visit
        if ($data['is_primary'] ?? false) {
            $visit->diagnoses()->where('id', '!=', $diagnosis->id)->update(['is_primary' => false]);
        }

        $diagnosis->update($data);

        return redirect()->route('visits.diagnoses', $visit)->with('success', 'Diagnosis updated successfully.');
    }

    /**
     * Remove the specified diagnosis.
     */
    public function destroy(Visit $visit, Diagnosis $diagnosis): RedirectResponse
    {
        $diagnosis->delete();

        return redirect()->route('visits.diagnoses', $visit)->with('success', 'Diagnosis deleted successfully.');
    }
}
