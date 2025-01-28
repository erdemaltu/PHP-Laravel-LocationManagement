<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLocationRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'marker_color' => 'required|string|regex:/^#[A-Fa-f0-9]{6}$/',
        ];
    }

    /**
     * Custom error messages for validation failures.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'latitude.required' => 'The latitude field is required.',
            'longitude.required' => 'The longitude field is required.',
            'marker_color.required' => 'The marker_color field is required.',
            'marker_color.regex' => 'The marker_color must be a valid hexadecimal code.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
