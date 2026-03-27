<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CopyVisitDataRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $visit = $this->route('visit');
        $previousVisit = $this->route('previousVisit');

        // Must be same patient and user must be able to update both visits
        return $visit->patient_id === $previousVisit->patient_id &&
               $this->user()->can('update', $visit) &&
               $this->user()->can('view', $previousVisit);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Medical History selections
            'medical_history' => 'nullable|array',
            'medical_history.*' => 'string|in:past_medical_history,family_history,medications_current,allergies,therapy_in_use',

            // Diagnosis selections
            'diagnoses' => 'nullable|array',
            'diagnoses.*' => 'integer|exists:diagnoses,id',

            // Examination data selections
            'examination_data' => 'nullable|array',
            'examination_data.*' => 'string|in:visus_od,visus_os,iop_od,iop_os,anterior_segment_findings_od,anterior_segment_findings_os,posterior_segment_findings_od,posterior_segment_findings_os',

            // Refraction selections
            'refractions' => 'nullable|array',
            'refractions.*' => 'integer|exists:refractions,id',

            // Prescription selections
            'prescriptions' => 'nullable|array',
            'prescriptions.*' => 'integer|exists:prescriptions,id',

            // Spectacle prescription selections
            'spectacle_prescriptions' => 'nullable|array',
            'spectacle_prescriptions.*' => 'integer|exists:spectacle_prescriptions,id',

            // Treatment plan selections
            'treatment_plans' => 'nullable|array',
            'treatment_plans.*' => 'integer|exists:treatment_plans,id',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'medical_history.*.in' => 'Invalid medical history field selected.',
            'diagnoses.*.exists' => 'Selected diagnosis does not exist.',
            'examination_data.*.in' => 'Invalid examination field selected.',
            'refractions.*.exists' => 'Selected refraction does not exist.',
            'prescriptions.*.exists' => 'Selected prescription does not exist.',
            'spectacle_prescriptions.*.exists' => 'Selected spectacle prescription does not exist.',
            'treatment_plans.*.exists' => 'Selected treatment plan does not exist.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Ensure at least one item is selected
            $hasSelection = collect($this->only([
                'medical_history', 'diagnoses', 'examination_data',
                'refractions', 'prescriptions', 'spectacle_prescriptions', 'treatment_plans'
            ]))->filter()->isNotEmpty();

            if (!$hasSelection) {
                $validator->errors()->add('selection', 'Please select at least one item to copy.');
            }
        });
    }
}
