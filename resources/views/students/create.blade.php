@extends('layouts.app')

@section('title', 'Ajouter un Nouvel Étudiant')
@section('page-subtitle', 'Ajout d\'Étudiant')

@section('content')
    <x-breadcrumb :items="[['label' => 'Étudiants', 'url' => route('students.index')], ['label' => 'Ajouter un Étudiant']]" />

    <x-card title="Ajouter un Nouvel Étudiant"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
        class="mb-8">

        <form method="POST" action="{{ route('students.store') }}" enctype="multipart/form-data" class="space-y-6">
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
                    <x-input name="matricule" label="Matricule" placeholder="Entrez le matricule" :value="old('matricule')"
                        required />
                    <x-input name="date_of_birth" type="date" label="Date de naissance" :value="old('date_of_birth')" required />
                    <x-input name="gender" type="select" label="Genre" :options="['M' => 'Masculin', 'F' => 'Féminin']" :value="old('gender')"
                        placeholder="Sélectionner le genre" required />
                    <x-input name="place_of_birth" label="Lieu de naissance" placeholder="Entrez le lieu de naissance"
                        :value="old('place_of_birth')" />
                    <x-input name="situation" type="select" label="Situation" :options="['R' => 'Redoublant', 'NR' => 'Non Redoublant']" :value="old('situation')"
                        placeholder="Sélectionner la situation" required />
                </div>
            </div>

            {{-- Academic Information Section --}}
            <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                    <span>Informations Académiques</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- School Year Dropdown --}}
                    <x-input name="school_year_id" type="select" label="Année scolaire" :options="$schoolYears"
                        :value="old('school_year_id')" placeholder="Sélectionner une année scolaire" required id="school_year_select" />

                    {{-- Class Dropdown --}}
                    <x-input name="classe_id" type="select" label="Classe" :options="$classes" :value="old('classe_id')"
                        placeholder="Sélectionner une classe" required id="class_select" />
                </div>
            </div>

            @include('Students.partials.photo-upload-with-webcam', [
                'fileInputLabel' => 'Choisir une photo',
                'previewInitialSrc' => null,
                'previewAlt' => '',
            ])

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                <x-button href="{{ route('students.index') }}" variant="secondary">
                    Annuler
                </x-button>
                <x-button type="submit" variant="primary"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'>
                    Créer l'Étudiant
                </x-button>
            </div>
        </form>
    </x-card>

    {{-- Dynamic Dropdown Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const schoolYearSelect = document.getElementById('school_year_select');
            const classSelect = document.getElementById('class_select');

            if (schoolYearSelect && classSelect) {
                schoolYearSelect.addEventListener('change', function() {
                    const yearId = this.value;

                    // Clear class dropdown
                    classSelect.innerHTML = '<option value="">Sélectionner une classe</option>';
                    classSelect.disabled = true;

                    if (yearId) {
                        // Show loading state
                        classSelect.innerHTML = '<option value="">Chargement...</option>';

                        // Fetch classes for selected year
                        fetch(`/api/classes/by-year/${yearId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Clear and populate class dropdown
                                    classSelect.innerHTML =
                                        '<option value="">Sélectionner une classe</option>';

                                    Object.entries(data.classes).forEach(([id, label]) => {
                                        const option = document.createElement('option');
                                        option.value = id;
                                        option.textContent = label;
                                        classSelect.appendChild(option);
                                    });

                                    classSelect.disabled = false;
                                } else {
                                    console.error('Error fetching classes:', data.message);
                                    classSelect.innerHTML =
                                        '<option value="">Erreur lors du chargement</option>';
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                classSelect.innerHTML =
                                    '<option value="">Erreur lors du chargement</option>';
                            });
                    } else {
                        classSelect.disabled = true;
                    }
                });
            }
        });
    </script>
@endsection
