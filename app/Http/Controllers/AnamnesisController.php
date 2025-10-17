<?php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnamnesisController extends Controller
{
    /**
     * Show anamnesis page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'anamnesis']);

        return view('visits.workspace.anamnesis', compact('visit'));
    }

    /**
     * Store or update anamnesis for a visit.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'chief_complaint' => 'nullable|string',
            'history_of_present_illness' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'family_history' => 'nullable|string',
            'medications_current' => 'nullable|string',
            'allergies' => 'nullable|string',
            'therapy_in_use' => 'nullable|string',
            'other_notes' => 'nullable|string',
        ]);

        $visit->anamnesis()->updateOrCreate(
            ['visit_id' => $visit->id],
            $request->only(['chief_complaint', 'history_of_present_illness', 'past_medical_history', 'family_history', 'medications_current', 'allergies', 'therapy_in_use', 'other_notes'])
        );

        return redirect()->route('visits.anamnesis', $visit)->with('success', __('his.messages.anamnesis_saved'));
    }
}
