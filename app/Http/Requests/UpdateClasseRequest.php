<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Form request for updating an existing class.
 */
class UpdateClasseRequest extends FormRequest
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
            'label' => [
                'required',
                'string',
                'max:255',
                Rule::unique('classes', 'label')->ignore($this->route('classe')),
            ],
            'pays' => 'nullable|string|max:255',
            'situation' => 'nullable|string|max:255',
            'year_id' => ['required', 'integer', 'exists:school_years,id'],
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
            'pays.max' => 'Le pays ne peut pas dépasser 255 caractères.',
            'situation.max' => 'La situation ne peut pas dépasser 255 caractères.',
            'year_id.required' => 'L\'année scolaire est obligatoire.',
            'year_id.integer' => 'L\'année scolaire doit être un nombre entier.',
            'year_id.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
        ];
    }
}
