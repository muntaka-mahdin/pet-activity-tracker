<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pet_id' => ['required', 'integer', 'exists:pets,id'],
            'activity_type' => ['required', 'string', 'max:255'],
            // Past activities from the last two years, or planned ones (like a vet visit) up to a year ahead.
            'activity_date' => [
                'required', 'date',
                'after_or_equal:'.now()->subYears(2)->toDateString(),
                'before_or_equal:'.now()->addYear()->toDateString(),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'pet_id.required' => 'Please choose a pet.',
            'activity_date.after_or_equal' => 'The date can be at most two years ago.',
            'activity_date.before_or_equal' => 'The date can be at most one year ahead.',
        ];
    }
}
