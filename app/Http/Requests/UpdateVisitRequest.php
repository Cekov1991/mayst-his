<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVisitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => ['required', 'exists:users,id'],
            'slot_id' => [
                'nullable',
                'exists:slots,id',
                Rule::exists('slots', 'id')->where(function ($query) {
                    if ($this->slot_id) {
                        $query->where('doctor_id', $this->doctor_id)
                            ->where('status', 'available');
                    }
                }),
            ],
            'type' => ['required', 'in:exam,control,surgery'],
            'status' => ['required', 'in:scheduled,arrived,in_progress,completed,cancelled'],
            'scheduled_at' => ['nullable', 'date'],
            'reason_for_visit' => ['required', 'string', 'max:1000'],
            'room' => ['nullable', 'string', 'max:50'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'patient_id' => __('visits.patient'),
            'doctor_id' => __('visits.doctor'),
            'slot_id' => __('Time Slot'),
            'type' => __('visits.type'),
            'status' => __('visits.status'),
            'scheduled_at' => __('visits.scheduled_at'),
            'reason_for_visit' => __('visits.reason_for_visit'),
            'room' => __('visits.room'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.exists' => __('validation.exists', ['attribute' => __('visits.patient')]),
            'doctor_id.exists' => __('validation.exists', ['attribute' => __('visits.doctor')]),
            'slot_id.exists' => __('The selected time slot is not available or does not belong to the selected doctor.'),
            'type.in' => __('validation.in', ['attribute' => __('visits.type')]),
            'status.in' => __('validation.in', ['attribute' => __('visits.status')]),
        ];
    }
}
