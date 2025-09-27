<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for updating an existing class.
 *
 * @package App\Http\Requests
 */
class UpdateClasseRequest extends FormRequest
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
    $classId = $this->route('classe')->id;

    return [
      'label' => [
        'required',
        'string',
        'max:255',
        'unique:classes,label,' . $classId . ',id,year_id,' . $this->input('year_id')
      ],
      'year_id' => 'required|exists:school_years,id',
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
      'label.unique' => 'Une classe avec ce nom existe déjà dans cette année scolaire.',
      'label.max' => 'Le nom de la classe ne peut pas dépasser 255 caractères.',
      'year_id.required' => 'L\'année scolaire est obligatoire.',
      'year_id.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
    ];
  }
}
