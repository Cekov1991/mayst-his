<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'patient_id' => ['required', 'exists:patients,id'],
            'doctor_id' => ['required', 'exists:users,id'],
            'type' => ['required', 'in:exam,control,surgery'],
            'status' => ['required', 'in:scheduled,arrived,in_progress,completed,cancelled'],
            'scheduled_at' => ['required', 'date'],
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
            'patient_id' => his_trans('visits.patient'),
            'doctor_id' => his_trans('visits.doctor'),
            'type' => his_trans('visits.type'),
            'status' => his_trans('visits.status'),
            'scheduled_at' => his_trans('visits.scheduled_at'),
            'reason_for_visit' => his_trans('visits.reason_for_visit'),
            'room' => his_trans('visits.room'),
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'patient_id.exists' => his_trans('validation.exists', ['attribute' => his_trans('visits.patient')]),
            'doctor_id.exists' => his_trans('validation.exists', ['attribute' => his_trans('visits.doctor')]),
            'type.in' => his_trans('validation.in', ['attribute' => his_trans('visits.type')]),
            'status.in' => his_trans('validation.in', ['attribute' => his_trans('visits.status')]),
        ];
    }
}
