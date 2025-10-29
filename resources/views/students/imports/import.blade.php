@extends('layouts.app')

@section('title', 'Importer des Étudiants')
@section('page-subtitle', 'Importer des Étudiants')

@section('content')
    <x-breadcrumb :items="[['label' => 'Étudiants', 'route' => route('students.index')], ['label' => 'Importer']]" />

    {{-- Upload Form Card --}}
    <x-card class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Importer des Étudiants</h1>
                    <p class="text-sm text-gray-600">Sélectionnez un fichier Excel/CSV pour importer les données</p>
                </div>
            </div>
        </div>

        {{-- Import Form --}}
        <form action="{{ route('students-import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- File Input Section --}}
            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
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
                                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-900">Format des colonnes Excel</h4>
                            <p class="text-xs font-light text-gray-700">Assurez-vous que les colonnes de votre fichier Excel
                                respectent le format suivant:</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Nom</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Matricule</span>
                                <div class="flex items-center space-x-1">
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Unique</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Date de naissance</span>
                                <div class="flex items-center space-x-1">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">jj/mm/aaaa</span>
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Optionnel</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Pays de naissance</span>
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Optionnel</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Situation</span>
                                <div class="flex items-center space-x-1">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">R/NR</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Genre</span>
                                <div class="flex items-center space-x-1">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">M/F</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Classe</span>
                                <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="font-medium text-gray-900">Année Scolaire</span>
                                <div class="flex items-center space-x-1">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full">aaaa-aaaa</span>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-full">Requis</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                <x-button href="{{ route('students.index') }}" variant="secondary" size="md">
                    Annuler
                </x-button>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500"
                    id="submitBtn">
                    Importer
                </button>
            </div>
        </form>
    </x-card>

    {{-- Import History --}}
    @if ($imports->count() > 0)
        <x-card>
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Historique des Imports</h2>
                        <p class="text-sm text-gray-600">Tous les imports effectués précédemment</p>
                    </div>
                </div>
            </div>

            {{-- Import History Table --}}
            <x-table :headers="[
                [
                    'field' => 'created_at',
                    'label' => 'Date/Heure',
                    'sortable' => true,
                    'route' => 'students-import.index',
                ],
                ['field' => 'filename', 'label' => 'Fichier', 'sortable' => true, 'route' => 'students-import.index'],
                ['label' => 'Utilisateur'],
                ['field' => 'total_rows', 'label' => 'Lignes', 'sortable' => true, 'route' => 'students-import.index'],
                [
                    'field' => 'success_count',
                    'label' => 'Réussis',
                    'sortable' => true,
                    'route' => 'students-import.index',
                ],
                [
                    'field' => 'failed_count',
                    'label' => 'Échecs',
                    'sortable' => true,
                    'route' => 'students-import.index',
                ],
                ['field' => 'status', 'label' => 'Statut', 'sortable' => true, 'route' => 'students-import.index'],
                ['label' => 'Actions', 'class' => 'text-right'],
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$imports"
                :itemLabel="'imports'">
                @foreach ($imports as $import)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>{{ $import->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $import->created_at->format('H:i:s') }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="truncate max-w-xs">{{ $import->filename }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $import->user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $import->total_rows }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-green-600">{{ $import->success_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-red-600">{{ $import->failed_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
                                @if ($import->isCompleted()) bg-green-100 text-green-800
                                @elseif($import->isFailed()) bg-red-100 text-red-800
                                @else bg-yellow-100 text-yellow-800 @endif">
                                @if ($import->isCompleted())
                                    Terminé
                                @elseif($import->isFailed())
                                    Échec
                                @else
                                    En cours
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <a href="{{ route('students-import.show', $import->id) }}"
                                class="text-indigo-600 hover:text-indigo-900">
                                Voir détails →
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    @endif
@endsection

@push('scripts')
    <script>
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

        // Handle form submission with loading state
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Import en cours...';
            submitBtn.disabled = true;
        });
    </script>
@endpush
