@extends('layouts.app')

@section('title', 'Modifier la Classe - ' . $classe->label)
@section('page-subtitle', 'Modification de Classe')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Classes', 'url' => route('classes.index')],
        ['label' => $classe->label, 'url' => route('classes.show', $classe)],
        ['label' => 'Modifier'],
    ]" />

    <x-card title="Modifier la Classe" subtitle="Modification des informations de {{ $classe->label }}"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
        class="mb-8">

        <form method="POST" action="{{ route('classes.update', $classe) }}" class="space-y-6">
            @csrf
            @method('PUT')

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
                        :value="old('label', $classe->label)" required />

                    {{-- School Year --}}
                    <x-input name="year_id" type="select" label="Année scolaire" :options="$schoolYears" :value="old('year_id', $classe->year_id)"
                        placeholder="Sélectionner une année scolaire" required />
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
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Conseils pour le nom de la classe</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Utilisez des formats cohérents (ex: "1ère A", "Terminale C")</li>
                                <li>• Évitez les caractères spéciaux et les espaces multiples</li>
                                <li>• Le nom doit être unique pour chaque année scolaire</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Class Statistics Section --}}
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    <span>Statistiques de la Classe</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Student Count --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nombre d'élèves</p>
                                <p class="text-2xl font-bold text-gray-900">
                                    {{ $classe->students_count ?? $classe->students->count() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- School Year --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Année scolaire</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $classe->schoolYear->year ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Creation Date --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Créée le</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $classe->created_at->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-button href="{{ route('classes.show', $classe) }}" variant="secondary">
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
