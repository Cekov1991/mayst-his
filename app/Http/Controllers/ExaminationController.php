<?php

namespace App\Http\Controllers;

use App\Models\Refraction;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExaminationController extends Controller
{
    /**
     * Show examination page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'ophthalmicExam.refractions']);

        return view('visits.workspace.examination', compact('visit'));
    }

    /**
     * Store or update ophthalmic exam for a visit.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'visus_od' => 'nullable|string|max:50',
            'visus_os' => 'nullable|string|max:50',
            'iop_od' => 'nullable|numeric|between:0,99.99',
            'iop_os' => 'nullable|numeric|between:0,99.99',
            'anterior_segment_findings_od' => 'nullable|string',
            'posterior_segment_findings_od' => 'nullable|string',
            'anterior_segment_findings_os' => 'nullable|string',
            'posterior_segment_findings_os' => 'nullable|string',
        ]);

        $visit->ophthalmicExam()->updateOrCreate(
            ['visit_id' => $visit->id],
            $request->only(['visus_od', 'visus_os', 'iop_od', 'iop_os', 'anterior_segment_findings_od', 'posterior_segment_findings_od', 'anterior_segment_findings_os', 'posterior_segment_findings_os'])
        );

        return redirect()->route('visits.examination', $visit)->with('success', __('his.messages.exam_saved'));
    }

    /**
     * Show create refraction form.
     */
    public function createRefraction(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.refraction-create', compact('visit'));
    }

    /**
     * Store a refraction measurement.
     */
    public function storeRefraction(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'eye' => 'required|in:OD,OS',
            'method' => 'required|in:autorefraction,lensmeter,subjective',
            'sphere' => 'nullable|numeric|between:-30,30',
            'cylinder' => 'nullable|numeric|between:-10,10',
            'axis' => 'nullable|integer|between:1,180',
            'add_power' => 'nullable|numeric|between:0,5',
            'prism' => 'nullable|numeric|between:0,20',
            'base' => 'nullable|in:up,down,in,out',
            'notes' => 'nullable|string',
        ]);

        // Get or create the ophthalmic exam first
        $exam = $visit->ophthalmicExam()->firstOrCreate(['visit_id' => $visit->id]);

        $exam->refractions()->create($request->only(['eye', 'method', 'sphere', 'cylinder', 'axis', 'add_power', 'prism', 'base', 'notes']));

        return redirect()->route('visits.examination', $visit)->with('success', 'Refraction saved successfully.');
    }

    /**
     * Delete a refraction measurement.
     */
    public function destroyRefraction(Visit $visit, Refraction $refraction): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $refraction->delete();

        return redirect()->route('visits.examination', $visit)->with('success', 'Refraction deleted successfully.');
    }
}
