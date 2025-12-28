@extends('layouts.app')

@section('title', isset($currentClasse) ? 'Élèves de la Classe : ' . $currentClasse->label : 'Gestion des Étudiants')
@section('page-subtitle', isset($currentClasse) ? 'Étudiants - ' . $currentClasse->label : 'Gestion des Étudiants')

@section('content')
    <x-breadcrumb :items="[['label' => 'Étudiants']]" />

    {{-- Single Card: Complete Students Management --}}
    <x-card class="mb-8">
        {{-- Card Header: Title and Add Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800">Catalogue des Étudiants</h1>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                {{-- Import Students Button --}}
                @can('import_students')
                    <button type="button" onclick="openImportModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                        </svg>
                        Importer des Étudiants
                    </button>

                    {{-- Import Photos Button --}}
                    <button type="button" onclick="openImportPhotosModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Importer des Photos
                    </button>
                @endcan

                {{-- Add New Student Button --}}
                @can('create_students')
                    <x-button href="{{ route('students.create') }}" variant="primary" size="md"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                        Nouvel Étudiant
                    </x-button>
                @endcan
            </div>
        </div>

        {{-- Horizontal Divider --}}
        <div class="border-t border-gray-200 mb-6"></div>

        {{-- Filters Section --}}
        <form method="GET" action="{{ route('students.index') }}" class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Filters Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                    {{-- Search Filter --}}
                    <div class="relative flex-1 max-w-md">
                        <input type="search" name="search" placeholder="Rechercher..." value="{{ request('search') }}"
                            class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    {{-- Classe Filter --}}
                    <div class="flex items-center space-x-2">
                        <label for="classe_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Classe:</label>
                        <select name="classe_id" id="classe_id" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                            <option value="">Toutes les classes</option>
                            @if (isset($allClasses) && is_iterable($allClasses))
                                @foreach ($allClasses as $id => $label)
                                    <option value="{{ $id }}" {{ request('classe_id') == $id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Reset Button --}}
                @if (request()->hasAny(['search', 'classe_id']))
                    <div class="flex justify-end">
                        <x-button type="button" variant="secondary" size="md"
                            onclick="window.location.href='{{ route('students.index') }}'"
                            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
                            class="bg-red-600 hover:bg-red-700 text-white">
                            Réinitialiser les filtres
                        </x-button>
                    </div>
                @endif
            </div>
        </form>

        {{-- Students Table Section --}}
        @if ($students->count() > 0)
            <x-table :headers="[
                ['label' => 'Photo'],
                ['field' => 'name', 'label' => 'Nom & Prénom', 'sortable' => true, 'route' => 'students.index'],
                ['field' => 'matricule', 'label' => 'Matricule', 'sortable' => true, 'route' => 'students.index'],
                ['label' => 'Classe'],
                [
                    'field' => 'date_of_birth',
                    'label' => 'Date de naissance',
                    'sortable' => true,
                    'route' => 'students.index',
                ],
                ['field' => 'gender', 'label' => 'Genre', 'sortable' => true, 'route' => 'students.index'],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$students"
                :itemLabel="'étudiants'">

                @foreach ($students as $student)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        {{-- Photo --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        </td>

                        {{-- Name --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                        </td>

                        {{-- Matricule --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700 font-medium">{{ $student->matricule ?? 'N/A' }}</div>
                        </td>

                        {{-- Class --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $student->classe->label ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Birth Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                        </td>

                        {{-- Gender --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->gender === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-1">
                                {{-- View Button --}}
                                @can('view_students')
                                    <x-button href="{{ route('students.show', $student) }}" variant="ghost" size="sm"
                                        title="Voir les détails"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                        title="Voir les détails"
                                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 border-blue-200 hover:border-blue-300" />
                                @endcan

                                {{-- Edit Button --}}
                                @can('edit_students')
                                    <x-button href="{{ route('students.edit', $student) }}" variant="ghost" size="sm"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                        title="Modifier"
                                        class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border-indigo-200 hover:border-indigo-300" />
                                @endcan

                                {{-- Delete Button --}}
                                @can('delete_students')
                                    <form method="POST" action="{{ route('students.destroy', $student) }}"
                                        class="inline-block"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'étudiant {{ $student->name }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="ghost" size="sm"
                                            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                            title="Supprimer"
                                            class="text-red-600 hover:text-red-800 hover:bg-red-50 border-red-200 hover:border-red-300" />
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    @if (request()->hasAny(['search', 'classe_id', 'year']))
                        Aucun étudiant trouvé
                    @else
                        Aucun étudiant enregistré
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    @if (request()->hasAny(['search', 'classe_id', 'year']))
                        Aucun étudiant ne correspond aux critères de recherche.
                    @else
                        Commencez par ajouter votre premier étudiant.
                    @endif
                </p>
                <x-button href="{{ route('students.create') }}" variant="primary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                    Ajouter un étudiant
                </x-button>
            </div>
        @endif
    </x-card>

    {{-- Import Modal --}}
    <div id="importModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Importer des Étudiants</h3>
                            <p class="text-sm text-gray-600">Sélectionnez un fichier Excel/CSV pour importer les données
                            </p>
                        </div>
                    </div>
                    <button onclick="closeImportModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form id="importForm" action="{{ route('students.import') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- File Input Section --}}
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="file" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Cliquez pour sélectionner un fichier
                                    </span>
                                    <span class="mt-1 block text-sm text-gray-500">
                                        ou glissez-déposez votre fichier ici
                                    </span>
                                </label>
                                <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required
                                    class="sr-only">

                                <!-- File Selected Indicator (hidden by default) -->
                                <div id="fileSelectedIndicator"
                                    class="hidden mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-green-900" id="fileName"></p>
                                            <p class="text-xs text-green-700" id="fileSize"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Formats acceptés: .xlsx, .xls, .csv (max 10MB)
                            </p>
                        </div>
                    </div>

                    {{-- Format Instructions --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-blue-900 mb-3">Format des colonnes Excel</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">nom</span>
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">matricule</span>
                                            <div class="flex items-center space-x-1">
                                                <span
                                                    class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                                <span
                                                    class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">Unique</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">date_naissance</span>
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Optionnel</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">pays_naissance</span>
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Optionnel</span>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">situation</span>
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">R/NR</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">genre</span>
                                            <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">M/F</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">classe</span>
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="font-medium text-gray-900">annee_scolaire</span>
                                            <span
                                                class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeImportModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Annuler
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Importer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- JavaScript for Modal --}}
    <script>
        function openImportModal() {
            document.getElementById('importModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImportModal() {
            document.getElementById('importModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('importForm').reset();

            // Reset file indicator
            document.getElementById('fileSelectedIndicator').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('importModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportModal();
            }
        });

        // Handle form submission with loading state
        document.getElementById('importForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Import en cours...';
            submitBtn.disabled = true;
        });

        // Handle file input change
        document.getElementById('file').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const indicator = document.getElementById('fileSelectedIndicator');
            const fileNameEl = document.getElementById('fileName');
            const fileSizeEl = document.getElementById('fileSize');

            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                // Show the indicator
                indicator.classList.remove('hidden');
                fileNameEl.textContent = fileName;
                fileSizeEl.textContent = `Taille: ${fileSize} MB`;
            } else {
                // Hide the indicator if no file selected
                indicator.classList.add('hidden');
            }
        });
    </script>

    {{-- Import Photos Modal --}}
    <div id="importPhotosModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Importer des Photos</h3>
                            <p class="text-sm text-gray-600">Sélectionnez plusieurs photos pour les associer automatiquement aux étudiants</p>
                        </div>
                    </div>
                    <button onclick="closeImportPhotosModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form id="importPhotosForm" action="{{ route('students.import-photos') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    {{-- File Input Section --}}
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <label for="photos" class="cursor-pointer">
                                    <span class="mt-2 block text-sm font-medium text-gray-900">
                                        Cliquez pour sélectionner des photos
                                    </span>
                                    <span class="mt-1 block text-sm text-gray-500">
                                        ou glissez-déposez vos photos ici
                                    </span>
                                </label>
                                <input type="file" id="photos" name="photos[]" accept="image/*" multiple required
                                    class="sr-only">

                                <!-- Files Selected Indicator (hidden by default) -->
                                <div id="photosSelectedIndicator"
                                    class="hidden mt-3 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-green-900" id="photosCount"></p>
                                            <p class="text-xs text-green-700" id="photosTotalSize"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Formats acceptés: JPEG, PNG, JPG, GIF, WEBP (max 2MB par fichier, jusqu'à 400 fichiers)
                            </p>
                        </div>
                    </div>

                    {{-- Format Instructions --}}
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-blue-900 mb-2">Instructions importantes:</h4>
                                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                                    <li>Nommez chaque photo avec le <strong>matricule</strong> de l'étudiant suivi de l'extension</li>
                                    <li>Exemple: <code class="bg-blue-100 px-1 rounded">12345.jpg</code>, <code class="bg-blue-100 px-1 rounded">67890.png</code></li>
                                    <li>Le système associera automatiquement chaque photo à l'étudiant correspondant</li>
                                    <li>Les photos existantes seront remplacées si elles ont le même matricule</li>
                                    <li>Les fichiers avec des matricules introuvables seront ignorés</li>
                                    <li><strong>Note:</strong> Pour importer 100-200 photos ou plus, configurez PHP avec: <code class="bg-blue-100 px-1 rounded">max_file_uploads ≥ 400</code>, <code class="bg-blue-100 px-1 rounded">upload_max_filesize ≥ 2M</code>, et <code class="bg-blue-100 px-1 rounded">post_max_size ≥ 800M</code> (voir PHP_CONFIG_GUIDE.md)</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <button type="button" onclick="closeImportPhotosModal()"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Annuler
                        </button>
                        <button type="submit" id="importPhotosSubmitBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500">
                            Importer les Photos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Detailed Import Report Display --}}
    @if (session('photos_import_report'))
        @php
            $report = session('photos_import_report');
        @endphp
        <div id="photosImportReport" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center p-2 sm:p-4">
            <div class="relative bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[95vh] overflow-hidden flex flex-col">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center space-x-2 sm:space-x-3">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900">Rapport d'import des photos</h3>
                    </div>
                    <button onclick="closePhotosImportReport()" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                    {{-- Success Summary --}}
                    <div class="mb-4 sm:mb-6">
                        <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-base sm:text-lg font-semibold text-gray-900">Résumé</h4>
                        </div>
                        <div class="bg-green-50 border border-green-200 rounded-lg p-3 sm:p-4 space-y-2">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-0">
                                <span class="text-xs sm:text-sm text-gray-700">Fichiers reçus par le serveur:</span>
                                <span class="font-bold text-gray-900 text-sm sm:text-base">{{ $report['received'] ?? ($report['imported'] ?? 0) }}</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-0">
                                <span class="text-xs sm:text-sm text-gray-700">Photos importées avec succès:</span>
                                <span class="font-bold text-green-700 text-sm sm:text-base">{{ $report['imported'] ?? 0 }}</span>
                            </div>
                            @if (isset($report['max_file_uploads']) && $report['max_file_uploads'] && ($report['received'] ?? 0) >= $report['max_file_uploads'])
                                <div class="mt-2 p-2 sm:p-3 bg-yellow-50 border border-yellow-200 rounded">
                                    <p class="text-xs sm:text-sm text-yellow-800">
                                        ⚠️ Limite PHP détectée: max_file_uploads = {{ $report['max_file_uploads'] }}.
                                        Si vous avez sélectionné plus de fichiers, certains n'ont peut-être pas été envoyés.
                                        Augmentez max_file_uploads dans php.ini.
                                    </p>
                                </div>
                            @endif
                            @if (($report['replaced'] ?? 0) > 0)
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 sm:gap-0">
                                    <span class="text-xs sm:text-sm text-gray-700">Photos remplacées:</span>
                                    <span class="font-bold text-blue-700 text-sm sm:text-base">{{ $report['replaced'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Not Found Photos --}}
                    @if (!empty($report['not_found']))
                        <div class="mb-4 sm:mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3 sm:mb-4">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900">
                                        Photos non associées ({{ count($report['not_found']) }})
                                    </h4>
                                </div>
                                @if (count($report['not_found']) > 20)
                                    <div class="flex items-center space-x-2">
                                        <input type="text" id="searchNotFound" placeholder="Rechercher un fichier..."
                                            class="text-xs sm:text-sm px-3 py-1.5 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full sm:w-auto">
                                        <button onclick="clearSearchNotFound()" class="text-xs sm:text-sm text-gray-600 hover:text-gray-800 px-2">
                                            Effacer
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                                <p class="text-xs sm:text-sm text-red-700 mb-3 sm:mb-4">
                                    Les matricules suivants n'ont pas été trouvés dans la base de données:
                                </p>
                                <div class="max-h-96 sm:max-h-[500px] overflow-y-auto mb-2">
                                    <div id="notFoundList" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                                        @foreach ($report['not_found'] as $index => $filename)
                                            <div class="not-found-item bg-white border border-red-200 rounded-md p-2 sm:p-3 flex items-start space-x-2 hover:border-red-400 hover:shadow-sm transition-all"
                                                 data-filename="{{ strtolower($filename) }}">
                                                <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <code class="text-xs sm:text-sm text-red-800 break-all bg-red-50 px-2 py-1 rounded flex-1 font-mono">{{ $filename }}</code>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div id="notFoundCount" class="flex items-center justify-between text-xs sm:text-sm text-red-600">
                                    <span>
                                        <span id="visibleCount">{{ count($report['not_found']) }}</span> / {{ count($report['not_found']) }} fichier(s) visible(s)
                                    </span>
                                    @if (count($report['not_found']) > 20)
                                        <span class="italic">Utilisez la recherche pour filtrer les résultats</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if (count($report['not_found']) > 20)
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const searchInput = document.getElementById('searchNotFound');
                                    const notFoundItems = document.querySelectorAll('.not-found-item');
                                    const visibleCountEl = document.getElementById('visibleCount');

                                    function filterNotFound() {
                                        const searchTerm = searchInput.value.toLowerCase().trim();
                                        let visibleCount = 0;

                                        notFoundItems.forEach(item => {
                                            const filename = item.getAttribute('data-filename');
                                            if (filename.includes(searchTerm)) {
                                                item.style.display = '';
                                                visibleCount++;
                                            } else {
                                                item.style.display = 'none';
                                            }
                                        });

                                        visibleCountEl.textContent = visibleCount;
                                    }

                                    searchInput?.addEventListener('input', filterNotFound);

                                    window.clearSearchNotFound = function() {
                                        searchInput.value = '';
                                        filterNotFound();
                                    };
                                });
                            </script>
                        @endif
                    @endif

                    {{-- Errors --}}
                    @if (!empty($report['errors']))
                        <div class="mb-4 sm:mb-6">
                            <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                                <svg class="w-5 h-5 text-yellow-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                    </path>
                                </svg>
                                <h4 class="text-base sm:text-lg font-semibold text-gray-900">
                                    Erreurs ({{ count($report['errors']) }})
                                </h4>
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4">
                                <div class="max-h-96 sm:max-h-[400px] overflow-y-auto space-y-2">
                                    @foreach ($report['errors'] as $error)
                                        <div class="bg-white border border-yellow-200 rounded-md p-2 sm:p-3 flex items-start space-x-2 hover:border-yellow-400 hover:shadow-sm transition-all">
                                            <svg class="w-4 h-4 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                                </path>
                                            </svg>
                                            <span class="text-xs sm:text-sm text-yellow-800 flex-1 break-words">{{ $error }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="flex justify-end space-x-3 p-4 sm:p-6 border-t border-gray-200 bg-gray-50 flex-shrink-0">
                    <button onclick="closePhotosImportReport()"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 text-sm sm:text-base">
                        Fermer
                    </button>
                </div>
            </div>
        </div>

        <style>
            /* Style du scrollbar pour les listes dans le modal */
            #photosImportReport .overflow-y-auto::-webkit-scrollbar {
                width: 10px;
                height: 10px;
            }
            #photosImportReport .overflow-y-auto::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 5px;
            }
            #photosImportReport .overflow-y-auto::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 5px;
                border: 2px solid #f1f1f1;
            }
            #photosImportReport .overflow-y-auto::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
            /* Style pour Firefox */
            #photosImportReport .overflow-y-auto {
                scrollbar-width: thin;
                scrollbar-color: #888 #f1f1f1;
            }
            /* Animation pour les éléments filtrés */
            .not-found-item {
                transition: opacity 0.2s ease, transform 0.2s ease;
            }
            .not-found-item[style*="display: none"] {
                display: none !important;
            }
        </style>

        <script>
            function closePhotosImportReport() {
                document.getElementById('photosImportReport').remove();
            }

            // Close on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('photosImportReport')) {
                    closePhotosImportReport();
                }
            });

            // Close when clicking outside
            document.getElementById('photosImportReport')?.addEventListener('click', function(e) {
                if (e.target === this) {
                    closePhotosImportReport();
                }
            });
        </script>
    @endif

    {{-- JavaScript for Photos Import Modal --}}
    <script>
        function openImportPhotosModal() {
            document.getElementById('importPhotosModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeImportPhotosModal() {
            document.getElementById('importPhotosModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            document.getElementById('importPhotosForm').reset();
            document.getElementById('photosSelectedIndicator').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('importPhotosModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportPhotosModal();
            }
        });

        // Handle form submission with loading state
        document.getElementById('importPhotosForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('importPhotosSubmitBtn');
            submitBtn.textContent = 'Import en cours...';
            submitBtn.disabled = true;
        });

        // Handle file input change
        document.getElementById('photos')?.addEventListener('change', function(e) {
            const files = e.target.files;
            const indicator = document.getElementById('photosSelectedIndicator');
            const photosCountEl = document.getElementById('photosCount');
            const photosTotalSizeEl = document.getElementById('photosTotalSize');

            if (files && files.length > 0) {
                // Vérifier la limite de fichiers
                if (files.length > 400) {
                    alert('Vous ne pouvez pas sélectionner plus de 400 fichiers à la fois. Veuillez réduire le nombre de fichiers.');
                    this.value = '';
                    indicator.classList.add('hidden');
                    return;
                }

                const totalSize = Array.from(files).reduce((sum, file) => sum + file.size, 0);
                const totalSizeMB = (totalSize / 1024 / 1024).toFixed(2);
                const maxSizeMB = 800; // 400 fichiers * 2MB max par fichier

                // Avertissement si la taille totale dépasse 500MB (pour laisser une marge)
                if (totalSize > 500 * 1024 * 1024) {
                    const confirmMessage = `Attention: Vous avez sélectionné ${files.length} fichiers (${totalSizeMB} MB).\n\n` +
                        `Pour un upload réussi, assurez-vous que:\n` +
                        `- max_file_uploads ≥ ${files.length}\n` +
                        `- post_max_size ≥ ${(totalSizeMB * 1.2).toFixed(0)}M\n\n` +
                        `Voulez-vous continuer l'import?`;
                    if (!confirm(confirmMessage)) {
                        this.value = '';
                        indicator.classList.add('hidden');
                        return;
                    }
                }

                indicator.classList.remove('hidden');
                photosCountEl.textContent = `${files.length} fichier(s) sélectionné(s)`;
                photosTotalSizeEl.textContent = `Taille totale: ${totalSizeMB} MB`;
            } else {
                indicator.classList.add('hidden');
            }
        });
    </script>
@endsection
