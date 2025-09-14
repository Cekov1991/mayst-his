<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVisitRequest extends FormRequest
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
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:exam,control,surgery'],
            'status' => ['required', 'in:scheduled,arrived,in_progress,completed,cancelled'],
            'scheduled_at' => ['required', 'date', 'after_or_equal:today'],
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
            'patient_id' => __('his.visits.patient'),
            'doctor_id' => __('his.visits.doctor'),
            'type' => __('his.visits.type'),
            'status' => __('his.visits.status'),
            'scheduled_at' => __('his.visits.scheduled_at'),
            'reason_for_visit' => __('his.visits.reason_for_visit'),
            'room' => __('his.visits.room'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.exists' => __('his.validation.exists', ['attribute' => __('his.visits.patient')]),
            'doctor_id.exists' => __('his.validation.exists', ['attribute' => __('his.visits.doctor')]),
            'type.in' => __('his.validation.in', ['attribute' => __('his.visits.type')]),
            'status.in' => __('his.validation.in', ['attribute' => __('his.visits.status')]),
            'scheduled_at.after_or_equal' => __('his.validation.after_or_equal', [
                'attribute' => __('his.visits.scheduled_at'),
                'date' => 'today'
            ]),
        ];
    }
}
