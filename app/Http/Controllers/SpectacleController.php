<?php

namespace App\Http\Controllers;

use App\Models\SpectaclePrescription;
use App\Models\Visit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpectacleController extends Controller
{
    /**
     * Show spectacles page for a visit.
     */
    public function show(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor', 'spectaclePrescriptions.doctor']);

        return view('visits.workspace.spectacles', compact('visit'));
    }

    /**
     * Show create spectacles form.
     */
    public function create(Visit $visit): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.spectacles-create', compact('visit'));
    }

    /**
     * Store a spectacle prescription.
     */
    public function store(Request $request, Visit $visit): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'type' => 'required|in:distance,near,bifocal,progressive',
            'od_sphere' => 'nullable|numeric|between:-30,30',
            'od_cylinder' => 'nullable|numeric|between:-10,10',
            'od_axis' => 'nullable|integer|between:1,180',
            'od_add' => 'nullable|numeric|between:0,5',
            'os_sphere' => 'nullable|numeric|between:-30,30',
            'os_cylinder' => 'nullable|numeric|between:-10,10',
            'os_axis' => 'nullable|integer|between:1,180',
            'os_add' => 'nullable|numeric|between:0,5',
            'pd_distance' => 'nullable|numeric|between:40,80',
            'pd_near' => 'nullable|numeric|between:40,80',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
        ]);

        $data = $request->only(['type', 'od_sphere', 'od_cylinder', 'od_axis', 'od_add', 'os_sphere', 'os_cylinder', 'os_axis', 'os_add', 'pd_distance', 'pd_near', 'notes', 'valid_until']);
        $data['doctor_id'] = Auth::id();

        $visit->spectaclePrescriptions()->create($data);

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription saved successfully.');
    }

    /**
     * Show edit spectacles form.
     */
    public function edit(Visit $visit, SpectaclePrescription $spectacle): View
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $visit->load(['patient', 'doctor']);

        return view('visits.workspace.spectacles-edit', compact('visit', 'spectacle'));
    }

    /**
     * Update a spectacle prescription.
     */
    public function update(Request $request, Visit $visit, SpectaclePrescription $spectacle): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $request->validate([
            'type' => 'required|in:distance,near,bifocal,progressive',
            'od_sphere' => 'nullable|numeric|between:-30,30',
            'od_cylinder' => 'nullable|numeric|between:-10,10',
            'od_axis' => 'nullable|integer|between:1,180',
            'od_add' => 'nullable|numeric|between:0,5',
            'os_sphere' => 'nullable|numeric|between:-30,30',
            'os_cylinder' => 'nullable|numeric|between:-10,10',
            'os_axis' => 'nullable|integer|between:1,180',
            'os_add' => 'nullable|numeric|between:0,5',
            'pd_distance' => 'nullable|numeric|between:40,80',
            'pd_near' => 'nullable|numeric|between:40,80',
            'notes' => 'nullable|string',
            'valid_until' => 'nullable|date',
        ]);

        $spectacle->update($request->only(['type', 'od_sphere', 'od_cylinder', 'od_axis', 'od_add', 'os_sphere', 'os_cylinder', 'os_axis', 'os_add', 'pd_distance', 'pd_near', 'notes', 'valid_until']));

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription updated successfully.');
    }

    /**
     * Delete a spectacle prescription.
     */
    public function destroy(Visit $visit, SpectaclePrescription $spectacle): RedirectResponse
    {
        $this->authorize('accessMedicalWorkspace', $visit);

        $spectacle->delete();

        return redirect()->route('visits.spectacles', $visit)->with('success', 'Spectacle prescription deleted successfully.');
    }
}
