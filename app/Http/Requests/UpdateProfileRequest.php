<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.auth()->id()],
            'old_password' => [
                function ($attribute, $value, $fail) {
                    // Only validate old password if a new password is actually being set
                    if (! $this->filled('password')) {
                        return;
                    }

                    // When changing password, old password becomes required and must be a non-empty string
                    if (! is_string($value) || $value === '') {
                        $fail('Le mot de passe actuel est obligatoire pour changer le mot de passe.');
                        return;
                    }

                    if (! password_verify($value, auth()->user()->password)) {
                        $fail('Le mot de passe actuel est incorrect.');
                    }
                },
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
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
            'name.required' => 'Le nom est obligatoire.',
            'name.string' => 'Le nom doit être une chaîne de caractères.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'L\'email existe déjà.',
            'password.string' => 'Le nouveau mot de passe doit être une chaîne de caractères.',
            'password.min' => 'Le nouveau mot de passe doit être au moins 6 caractères.',
            'password.confirmed' => 'Les nouveaux mots de passe ne correspondent pas.',
        ];
    }

}
