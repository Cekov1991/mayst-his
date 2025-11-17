<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('schedule'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'doctor_id' => ['sometimes', 'required', 'exists:users,id'],
            'name' => ['nullable', 'string', 'max:255'],
            'valid_from' => ['sometimes', 'required', 'date'],
            'valid_to' => ['sometimes', 'required', 'date', 'after_or_equal:valid_from'],
            'start_time' => ['sometimes', 'required', 'date_format:H:i'],
            'end_time' => ['sometimes', 'required', 'date_format:H:i', 'after:start_time'],
            'slot_interval' => ['sometimes', 'required', 'integer', 'in:15,30,45,60'],
            'days_of_week' => ['sometimes', 'required', 'array', 'min:1'],
            'days_of_week.*' => ['integer', 'min:0', 'max:6'],
            'week_pattern' => ['nullable', 'array'],
            'week_pattern.*' => ['integer', 'min:1', 'max:5'],
            'specific_dates' => ['nullable', 'array'],
            'specific_dates.*' => ['date'],
            'excluded_dates' => ['nullable', 'array'],
            'excluded_dates.*' => ['date'],
            'is_active' => ['sometimes', 'boolean'],
            'regenerate_slots' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'doctor_id' => __('schedules.doctor'),
            'name' => __('schedules.name'),
            'valid_from' => __('schedules.valid_from'),
            'valid_to' => __('schedules.valid_to'),
            'start_time' => __('schedules.start_time'),
            'end_time' => __('schedules.end_time'),
            'slot_interval' => __('schedules.slot_interval'),
            'days_of_week' => __('schedules.days_of_week'),
            'week_pattern' => __('schedules.week_pattern'),
            'specific_dates' => __('schedules.specific_dates'),
            'excluded_dates' => __('schedules.excluded_dates'),
            'is_active' => __('schedules.is_active'),
            'regenerate_slots' => __('schedules.regenerate_slots'),
        ];
    }

    public function validated($key = null, $default = null)
    {
        $validated = parent::validated($key, $default);
        $validated['slot_interval'] = (int) $validated['slot_interval'];
        return $validated;
    }
}
