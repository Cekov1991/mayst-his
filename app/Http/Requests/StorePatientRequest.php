<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'unique_master_citizen_number' => ['nullable', 'string', 'max:20', 'unique:patients,unique_master_citizen_number'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'first_name' => __('his.patients.first_name'),
            'last_name' => __('his.patients.last_name'),
            'sex' => __('his.patients.sex'),
            'dob' => __('his.patients.dob'),
            'phone' => __('his.patients.phone'),
            'email' => __('his.patients.email'),
            'address' => __('his.patients.address'),
            'city' => __('his.patients.city'),
            'country' => __('his.patients.country'),
            'unique_master_citizen_number' => __('his.patients.unique_master_citizen_number'),
            'notes' => __('his.patients.notes'),
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'required' => __('his.validation.required'),
            'email' => __('his.validation.email'),
            'date' => __('his.validation.date'),
            'before' => __('his.patients.dob') . ' ' . strtolower(__('his.validation.date')) . ' must be in the past.',
            'unique' => __('his.patients.unique_master_citizen_number') . ' has already been taken.',
            'max' => __('his.validation.max_length', ['max' => ':max']),
            'in' => 'The selected :attribute is invalid.',
        ];
    }
}
