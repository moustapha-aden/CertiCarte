@extends('layouts.app')

@section('title', 'Modifier l\'Utilisateur - ' . $user->name)
@section('page-subtitle', 'Modification d\'Utilisateur')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Utilisateurs', 'url' => route('users.index')],
        ['label' => $user->name, 'url' => route('users.show', $user)],
        ['label' => 'Modifier'],
    ]" />

    <x-card title="Modifier l'Utilisateur" subtitle="Modification des informations de {{ $user->name }}"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
        class="mb-8">

        <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Personal Information Section --}}
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <span>Informations Personnelles</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input name="name" label="Nom complet" placeholder="Entrez le nom complet" :value="old('name', $user->name)"
                        required />
                    <x-input name="email" type="email" label="Adresse email" placeholder="exemple@lycee-balbala.dj"
                        :value="old('email', $user->email)" required />
                </div>
            </div>

            {{-- Account Information Section --}}
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                        </path>
                    </svg>
                    <span>Informations du Compte</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input name="role" type="select" label="Rôle" :options="['admin' => 'Administrateur', 'secretary' => 'Secrétaire']" :value="old('role', $user->role)" required />
                    <x-input name="password" type="password" label="Nouveau mot de passe"
                        placeholder="Laisser vide pour conserver le mot de passe actuel" />
                </div>

                <div class="mt-6">
                    <x-input name="password_confirmation" type="password" label="Confirmer le nouveau mot de passe"
                        placeholder="Répétez le nouveau mot de passe" />
                </div>
            </div>

            {{-- Additional Information Section --}}
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                    <span>Informations Supplémentaires</span>
                </h3>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Conseils pour la modification</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Administrateur:</strong> Accès complet au système</li>
                                <li>• <strong>Secrétaire:</strong> Accès limité aux fonctionnalités de gestion</li>
                                <li>• Le mot de passe doit contenir au moins 6 caractères</li>
                                <li>• Laissez le champ mot de passe vide pour conserver le mot de passe actuel</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-button href="{{ route('users.show', $user) }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" variant="primary"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'>
                    Mettre à jour
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
