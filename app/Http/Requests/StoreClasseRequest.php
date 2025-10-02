<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for storing a new class.
 */
class StoreClasseRequest extends FormRequest
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
            'label' => ['required', 'string', 'max:255', 'unique:classes,label'],
            'year' => ['required', 'string', 'regex:/^\d{4}-\d{4}$/', 'max:9'],
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
            'label.required' => 'Le nom de la classe est obligatoire.',
            'label.string' => 'Le nom de la classe doit être une chaîne de caractères.',
            'label.max' => 'Le nom de la classe ne peut pas dépasser 255 caractères.',
            'label.unique' => 'Une classe avec ce nom existe déjà.',
            'year.required' => 'L\'année scolaire est obligatoire.',
            'year.string' => 'L\'année scolaire doit être une chaîne de caractères.',
            'year.regex' => 'L\'année scolaire doit être au format YYYY-YYYY (ex: 2024-2025).',
            'year.max' => 'L\'année scolaire ne peut pas dépasser 9 caractères.',
        ];
    }
}
