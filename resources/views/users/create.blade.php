@extends('layouts.app')

@section('title', 'Créer un Nouveau Membre du Personnel')
@section('page-subtitle', 'Création de Personnel')

@section('content')
    <x-breadcrumb :items="[['label' => 'Personnel', 'url' => route('users.index')], ['label' => 'Créer un membre du personnel']]" />

    <x-card title="Créer un Nouveau Membre du Personnel"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
        class="mb-8">

        <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
            @csrf

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
                    <x-input name="name" label="Nom complet" placeholder="Entrez le nom complet" :value="old('name')"
                        required />
                    <x-input name="email" type="email" label="Adresse email" placeholder="exemple@lycee-balbala.dj"
                        :value="old('email')" required />
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
                    <x-input name="password" type="password" label="Mot de passe" placeholder="Mot de passe sécurisé"
                        required />
                    <x-input name="password_confirmation" type="password" label="Confirmer le mot de passe"
                        placeholder="Répétez le mot de passe" required />
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
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Conseils pour la création de membre du
                                personnel</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Personnel:</strong> Accès limité aux fonctionnalités selon les permissions
                                    assignées</li>
                                <li>• Le mot de passe doit contenir au moins 6 caractères</li>
                                <li>• L'adresse email doit être unique dans le système</li>
                                <li>• Les permissions seront assignées après la création via la page de gestion des rôles
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-button href="{{ route('users.index') }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" variant="primary"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'>
                    Créer le Membre du Personnel
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
