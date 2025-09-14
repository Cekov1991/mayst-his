<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiagnosisRequest extends FormRequest
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
            'diagnosed_by' => ['required', 'exists:users,id'],
            'is_primary' => ['nullable', 'boolean'],
            'eye' => ['required', 'in:OD,OS,OU,NA'],
            'code' => ['nullable', 'string', 'max:255'],
            'code_system' => ['nullable', 'in:ICD-10,ICD-11,SNOMED,LOCAL'],
            'term' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:provisional,working,confirmed,ruled_out,resolved'],
            'onset_date' => ['nullable', 'date', 'before_or_equal:today'],
            'severity' => ['required', 'in:mild,moderate,severe,unknown'],
            'acuity' => ['required', 'in:acute,subacute,chronic,unknown'],
            'notes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'diagnosed_by.required' => 'The diagnosing doctor is required.',
            'diagnosed_by.exists' => 'The selected doctor is invalid.',
            'eye.required' => 'The eye selection is required.',
            'eye.in' => 'The eye must be one of: OD, OS, OU, NA.',
            'code_system.in' => 'The code system must be one of: ICD-10, ICD-11, SNOMED, LOCAL.',
            'term.required' => 'The diagnosis term is required.',
            'term.max' => 'The diagnosis term may not be greater than 255 characters.',
            'status.required' => 'The diagnosis status is required.',
            'status.in' => 'The status must be one of: provisional, working, confirmed, ruled out, resolved.',
            'onset_date.date' => 'The onset date must be a valid date.',
            'onset_date.before_or_equal' => 'The onset date cannot be in the future.',
            'severity.required' => 'The severity is required.',
            'severity.in' => 'The severity must be one of: mild, moderate, severe, unknown.',
            'acuity.required' => 'The acuity is required.',
            'acuity.in' => 'The acuity must be one of: acute, subacute, chronic, unknown.',
        ];
    }
}
