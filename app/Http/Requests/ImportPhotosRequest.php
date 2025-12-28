<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form request for importing student photos in bulk
 */
class ImportPhotosRequest extends FormRequest
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
            'photos' => 'required|array|max:400',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
            'photos.required' => 'Veuillez sélectionner au moins une photo à importer.',
            'photos.array' => 'Les photos doivent être envoyées sous forme de tableau.',
            'photos.max' => 'Vous ne pouvez pas importer plus de 400 photos à la fois.',
            'photos.*.required' => 'Tous les fichiers doivent être valides.',
            'photos.*.image' => 'Tous les fichiers doivent être des images.',
            'photos.*.mimes' => 'Les images doivent être au format JPEG, PNG, JPG, GIF ou WEBP.',
            'photos.*.max' => 'Chaque image ne peut pas dépasser 2MB.',
        ];
    }
}

