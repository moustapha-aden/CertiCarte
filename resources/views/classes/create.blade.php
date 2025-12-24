@extends('layouts.app')

@section('title', 'Créer une Nouvelle Classe')
@section('page-subtitle', 'Création de Classe')

@section('content')
    <x-breadcrumb :items="[['label' => 'Classes', 'url' => route('classes.index')], ['label' => 'Créer une classe']]" />

    <x-card title="Créer une Nouvelle Classe"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
        class="mb-8">

        <form method="POST" action="{{ route('classes.store') }}" class="space-y-6">
            @csrf

            {{-- Class Information Section --}}
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <span>Informations de la Classe</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Class Name --}}
                    <x-input name="label" label="Nom de la classe" placeholder="Ex: 1ère A, Terminale C, Seconde B"
                        :value="old('label')" required />

                    {{-- School Year --}}
                    <x-input name="year" label="Année scolaire" placeholder="Ex: 2024-2025, 2025-2026" :value="old('year')"
                        required />
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
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Conseils pour la création de classe</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• <strong>Nom de classe:</strong> Utilisez des formats cohérents</li>
                                <li>• <strong>Année scolaire:</strong> Format recommandé YYYY-YYYY (ex: 2024-2025)</li>
                                <li>• Évitez les caractères spéciaux et les espaces multiples</li>
                                <li>• Le nom doit être unique pour chaque année scolaire</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-button href="{{ route('classes.index') }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" variant="primary"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'>
                    Créer la Classe
                </x-button>
            </div>
        </form>
    </x-card>
@endsection
