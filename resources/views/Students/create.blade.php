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
                    <x-input name="matricule" label="Matricule" placeholder="Entrez le matricule" :value="old('matricule')" />
                    <x-input name="date_of_birth" type="date" label="Date de naissance" :value="old('date_of_birth')" required />
                    <x-input name="gender" type="select" label="Genre" :options="['male' => 'Masculin', 'female' => 'Féminin']" :value="old('gender')" required />
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
                    <x-input name="school_year_id" type="select" label="Année scolaire" :options="$schoolYears" :value="old('school_year_id')"
                        placeholder="Sélectionner une année scolaire" required id="school_year_select" />

                    {{-- Class Dropdown --}}
                    <x-input name="class_id" type="select" label="Classe" :options="$classes" :value="old('class_id')"
                        placeholder="Sélectionner une classe" required id="class_select" />
                </div>
            </div>

            {{-- Contact Information Section --}}
            {{-- <div class="border-b border-gray-200 pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Informations de Contact</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-input name="email" type="email" label="Email" placeholder="exemple@email.com" />
                    <x-input name="phone" label="Téléphone" placeholder="+253 XX XX XX XX" />
                    <x-input name="address" label="Adresse" placeholder="Adresse complète" />
                    <x-input name="parent_name" label="Nom du parent/tuteur" placeholder="Nom du parent ou tuteur" />
                </div>
            </div> --}}

            {{-- Photo Section --}}
            <div class="pb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Photo de Profil</span>
                </h3>

                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center overflow-hidden">
                            <img id="preview" class="w-full h-full object-cover hidden" alt="Preview">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <x-input name="photo" type="file" label="Choisir une photo" accept="image/*"
                            help="Formats acceptés: JPG, PNG, GIF. Taille max: 2MB" />
                    </div>
                </div>
            </div>

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

    {{-- Photo Preview Script --}}
    <script>
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('preview');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

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
