<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Will be handled by middleware/policies later
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $patientId = $this->route('patient');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'sex' => ['required', 'string', 'in:male,female,other,unknown'],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'unique_master_citizen_number' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('patients', 'unique_master_citizen_number')->ignore($patientId)
            ],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => his_trans('patients.first_name'),
            'last_name' => his_trans('patients.last_name'),
            'sex' => his_trans('patients.sex'),
            'dob' => his_trans('patients.dob'),
            'phone' => his_trans('patients.phone'),
            'email' => his_trans('patients.email'),
            'address' => his_trans('patients.address'),
            'city' => his_trans('patients.city'),
            'country' => his_trans('patients.country'),
            'unique_master_citizen_number' => his_trans('patients.unique_master_citizen_number'),
            'notes' => his_trans('patients.notes'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => his_trans('validation.required'),
            'email' => his_trans('validation.email'),
            'date' => his_trans('validation.date'),
            'before' => his_trans('patients.dob') . ' ' . strtolower(his_trans('validation.date')) . ' must be in the past.',
            'unique' => his_trans('patients.unique_master_citizen_number') . ' has already been taken.',
            'max' => his_trans('validation.max_length', ['max' => ':max']),
            'in' => 'The selected :attribute is invalid.',
        ];
    }
}
