<?php

namespace App\Http\Controllers;

use App\Models\TreatmentPlan;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TreatmentController extends Controller
{
    /**
     * Show treatments page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'treatmentPlans']);

        return view('visits.workspace.treatments', compact('visit'));
    }

    /**
     * Show create treatment form.
     */
    public function create(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.treatment-create', compact('visit'));
    }

    /**
     * Store a treatment plan.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'plan_type' => 'required|in:surgery,injection,medical,advice',
            'details' => 'nullable|string',
            'planned_date' => 'nullable|date',
        ]);

        $visit->treatmentPlans()->create($request->only(['plan_type', 'details', 'planned_date']));

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan saved successfully.');
    }

    /**
     * Show edit treatment form.
     */
    public function edit(Visit $visit, TreatmentPlan $treatment): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.treatment-edit', compact('visit', 'treatment'));
    }

    /**
     * Update a treatment plan.
     */
    public function update(Request $request, Visit $visit, TreatmentPlan $treatment): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'plan_type' => 'required|in:surgery,injection,medical,advice',
            'details' => 'nullable|string',
            'planned_date' => 'nullable|date',
        ]);

        $treatment->update($request->only(['plan_type', 'details', 'planned_date']));

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan updated successfully.');
    }

    /**
     * Delete a treatment plan.
     */
    public function destroy(Visit $visit, TreatmentPlan $treatment): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $treatment->delete();

        return redirect()->route('visits.treatments', $visit)->with('success', 'Treatment plan deleted successfully.');
    }
}
