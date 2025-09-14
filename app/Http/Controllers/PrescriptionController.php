<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PrescriptionController extends Controller
{
    /**
     * Show prescriptions page for a visit.
     */
    public function show(Visit $visit): View
    {
        $visit->load(['patient', 'doctor', 'prescriptions.doctor', 'prescriptions.prescriptionItems']);

        return view('visits.workspace.prescriptions', compact('visit'));
    }

    /**
     * Show create prescription form.
     */
    public function create(Visit $visit): View
    {
        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.prescription-create', compact('visit'));
    }

    /**
     * Store a prescription with items.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.form' => 'required|in:drops,ointment,tablet,capsule,other',
            'items.*.strength' => 'nullable|string|max:50',
            'items.*.dosage_instructions' => 'required|string|max:255',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.repeats' => 'nullable|integer|min:0',
        ]);

        $prescription = $visit->prescriptions()->create([
            'doctor_id' => Auth::id(),
            'notes' => $request->input('notes'),
        ]);

        foreach ($request->input('items', []) as $item) {
            $prescription->prescriptionItems()->create($item);
        }

        return redirect()->route('visits.prescriptions', $visit)->with('success', __('his.messages.prescription_saved'));
    }

    /**
     * Show edit prescription form.
     */
    public function edit(Visit $visit, Prescription $prescription): View
    {
        $visit->load(['patient', 'doctor']);
        $prescription->load('prescriptionItems');

        return view('visits.workspace.prescription-edit', compact('visit', 'prescription'));
    }

    /**
     * Update a prescription with items.
     */
    public function update(Request $request, Visit $visit, Prescription $prescription): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.drug_name' => 'required|string|max:255',
            'items.*.form' => 'required|in:drops,ointment,tablet,capsule,other',
            'items.*.strength' => 'nullable|string|max:50',
            'items.*.dosage_instructions' => 'required|string|max:255',
            'items.*.duration_days' => 'nullable|integer|min:1',
            'items.*.repeats' => 'nullable|integer|min:0',
        ]);

        $prescription->update([
            'notes' => $request->input('notes'),
        ]);

        // Delete existing items and recreate them
        $prescription->prescriptionItems()->delete();

        foreach ($request->input('items', []) as $item) {
            $prescription->prescriptionItems()->create($item);
        }

        return redirect()->route('visits.prescriptions', $visit)->with('success', __('his.messages.prescription_saved'));
    }

    /**
     * Delete a prescription.
     */
    public function destroy(Visit $visit, Prescription $prescription): RedirectResponse
    {
        $prescription->delete(); // This will also delete related items due to cascade

        return redirect()->route('visits.prescriptions', $visit)->with('success', 'Prescription deleted successfully.');
    }
}
