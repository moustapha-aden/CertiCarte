<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for storing a new class.
 *
 * @package App\Http\Requests
 */
class StoreClasseRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
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
      'year_id.required' => 'L\'année scolaire est obligatoire.',
      'year_id.integer' => 'L\'année scolaire doit être un nombre entier.',
      'year_id.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
    ];
  }
}
