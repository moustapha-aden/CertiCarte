<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for updating an existing student
 */
class UpdateStudentRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:10|unique:students,matricule,'.$this->route('student')->id,
            'date_of_birth' => 'required|date|before:today',
            'place_of_birth' => 'required|string|max:255',
            'situation' => 'required|in:NR,R',
            'gender' => 'required|in:M,F',
            'classe_id' => 'required|exists:classes,id',
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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
            'name.required' => 'Le nom de l\'étudiant est obligatoire.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'matricule.required' => 'Le matricule est obligatoire.',
            'matricule.unique' => 'Ce matricule est déjà utilisé par un autre étudiant.',
            'matricule.max' => 'Le matricule ne peut pas dépasser 10 caractères.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'place_of_birth.required' => 'Le pays de naissance est obligatoire.',
            'place_of_birth.max' => 'Le pays de naissance ne peut pas dépasser 255 caractères.',
            'situation.required' => 'La situation est obligatoire.',
            'situation.in' => 'La situation doit être NR ou R.',
            'gender.required' => 'Le genre est obligatoire.',
            'gender.in' => 'Le genre doit être masculin ou féminin.',
            'classe_id.required' => 'La classe est obligatoire.',
            'classe_id.exists' => 'La classe sélectionnée n\'existe pas.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'L\'image doit être au format JPEG, PNG, JPG, GIF ou SVG.',
            'photo.max' => 'L\'image ne peut pas dépasser 2MB.',
        ];
    }
}
