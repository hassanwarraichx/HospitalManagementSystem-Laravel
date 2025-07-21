<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDoctorRequest extends FormRequest
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
        $doctorId = $this->route('doctor')->id ?? $this->route('doctor');

        return [
            'name'              => ['required', 'string', 'max:255'],
            'email'             => ['required', 'email', Rule::unique('users', 'email')->ignore($doctorId)],
            'password'          => ['nullable', 'string', 'min:6', 'confirmed'],
            'specialization_id' => ['required', 'exists:specializations,id'],
            'availability'      => ['nullable', 'array'],
            'availability.*'    => ['array'],
            'availability.*.*.start' => ['nullable', 'date_format:H:i'],
            'availability.*.*.end'   => ['nullable', 'date_format:H:i'],
        ];
    }
}
