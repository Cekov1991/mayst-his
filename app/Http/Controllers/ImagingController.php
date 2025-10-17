<?php

namespace App\Http\Controllers;

use App\Models\ImagingStudy;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ImagingController extends Controller
{
    /**
     * Show imaging page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'imagingStudies.orderedBy', 'imagingStudies.performedBy']);

        return view('visits.workspace.imaging', compact('visit'));
    }

    /**
     * Show create imaging form.
     */
    public function create(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.imaging-create', compact('visit'));
    }

    /**
     * Store an imaging study.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'modality' => 'required|in:OCT,VF,US,FA,Biometry,Photo,Other',
            'eye' => 'required|in:OD,OS,OU,NA',
            'status' => 'required|in:ordered,done,reported',
            'performed_at' => 'nullable|date',
            'findings' => 'nullable|string',
        ]);

        $data = $request->only(['modality', 'eye', 'status', 'performed_at', 'findings']);
        $data['ordered_by'] = Auth::id();
        if ($data['status'] !== 'ordered' && $data['performed_at']) {
            $data['performed_by'] = Auth::id();
        }

        $visit->imagingStudies()->create($data);

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study saved successfully.');
    }

    /**
     * Show edit imaging form.
     */
    public function edit(Visit $visit, ImagingStudy $imaging): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.imaging-edit', compact('visit', 'imaging'));
    }

    /**
     * Update an imaging study.
     */
    public function update(Request $request, Visit $visit, ImagingStudy $imaging): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'modality' => 'required|in:OCT,VF,US,FA,Biometry,Photo,Other',
            'eye' => 'required|in:OD,OS,OU,NA',
            'status' => 'required|in:ordered,done,reported',
            'performed_at' => 'nullable|date',
            'findings' => 'nullable|string',
        ]);

        $data = $request->only(['modality', 'eye', 'status', 'performed_at', 'findings']);
        if ($data['status'] !== 'ordered' && $data['performed_at']) {
            $data['performed_by'] = Auth::id();
        }

        $imaging->update($data);

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study updated successfully.');
    }

    /**
     * Delete an imaging study.
     */
    public function destroy(Visit $visit, ImagingStudy $imaging): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $imaging->delete();

        return redirect()->route('visits.imaging', $visit)->with('success', 'Imaging study deleted successfully.');
    }
}
