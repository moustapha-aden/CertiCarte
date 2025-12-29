@extends('layouts.app')

@section('title', 'Catalogue des Étudiants')
@section('page-subtitle', 'Catalogue des Étudiants')

@section('content')
    <x-breadcrumb :items="[['label' => 'Étudiants']]" />

    {{-- Flash Messages --}}
    <x-flash-message />

    {{-- Single Card: Complete Students Management --}}
    <x-card class="mb-8">
        {{-- Card Header: Title, Filters, and Action Buttons --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            {{-- Title --}}
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800">Catalogue des Étudiants</h1>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                @can('import_students')
                    <a href="{{ route('students-import.index') }}"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        Importer des Étudiants
                    </a>
                @endcan
                @can('import_students')
                    <button type="button" onclick="openImportPhotosModal()"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Importer des Photos
                    </button>
                @endcan
                @can('create_students')
                    <x-button href="{{ route('students.create') }}" variant="primary" size="md"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                        Nouvel Étudiant
                    </x-button>
                @endcan
            </div>
        </div>

        {{-- Filters Section --}}
        <form method="GET" action="{{ route('students.index') }}" class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Search and Filters Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                    {{-- Search Filter --}}
                    <div class="relative flex-1 max-w-md">
                        <input type="search" name="search" placeholder="Rechercher..."
                            value="{{ request('search') }}"
                            class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    {{-- School Year Filter --}}
                    <div class="flex items-center space-x-2">
                        <label for="year_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Année scolaire:</label>
                        <select name="year_id" id="year_id" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                            <option value="">Toutes les années</option>
                            @foreach ($schoolYears as $id => $year)
                                <option value="{{ $id }}" {{ request('year_id') == $id ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Class Filter --}}
                    @if ($allClasses->count() > 0)
                        <div class="flex items-center space-x-2">
                            <label for="classe_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Classe:</label>
                            <select name="classe_id" id="classe_id" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                                <option value="">Toutes les classes</option>
                                @foreach ($allClasses as $id => $label)
                                    <option value="{{ $id }}" {{ request('classe_id') == $id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                {{-- Reset Button --}}
                @if (request()->hasAny(['search', 'year_id', 'classe_id']))
                    <div class="flex justify-end">
                        <x-button type="button" variant="secondary" size="md"
                            onclick="window.location.href='{{ route('students.index') }}'"
                            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
                            class="bg-red-600 hover:bg-red-700 text-white">
                            Réinitialiser
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
                ['field' => 'date_of_birth', 'label' => 'Date de naissance', 'sortable' => true, 'route' => 'students.index'],
                ['field' => 'gender', 'label' => 'Genre', 'sortable' => true, 'route' => 'students.index'],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$students"
                :itemLabel="'étudiants'">

                @foreach ($students as $student)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        {{-- Photo --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="w-10 h-10 rounded-full overflow-hidden bg-indigo-100">
                                <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                                    class="w-full h-full object-cover">
                            </div>
                        </td>

                        {{-- Name --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                        </td>

                        {{-- Matricule --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->matricule ?? 'N/A' }}</div>
                        </td>

                        {{-- Classe --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $student->classe->label ?? 'N/A' }}</div>
                        </td>

                        {{-- Date of Birth --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                {{ $student->date_of_birth ? $student->date_of_birth->format('d/m/Y') : 'N/A' }}
                            </div>
                        </td>

                        {{-- Gender --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $student->gender === 'Masculin' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $student->gender ?? 'N/A' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                @can('view_students')
                                    <a href="{{ route('students.show', $student) }}"
                                        class="text-indigo-600 hover:text-indigo-900" title="Voir">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                            </path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('edit_students')
                                    <a href="{{ route('students.edit', $student) }}" class="text-blue-600 hover:text-blue-900"
                                        title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                @endcan
                                @can('delete_students')
                                    <form action="{{ route('students.destroy', $student) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucun étudiant</h3>
                <p class="mt-1 text-sm text-gray-500">Commencez par créer un nouvel étudiant ou importer des données.</p>
                <div class="mt-6">
                    @can('create_students')
                        <x-button href="{{ route('students.create') }}" variant="primary" size="md">
                            Nouvel Étudiant
                        </x-button>
                    @endcan
                </div>
            </div>
        @endif
    </x-card>

    {{-- Import Photos Modal --}}
    <div id="importPhotosModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Importer des Photos d'Étudiants</h3>
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
                    <input type="hidden" name="total_files_selected" id="totalFilesSelectedInput">

                    {{-- File Input Section with Drag & Drop --}}
                    <div id="dropZone"
                        class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-6 transition-colors hover:border-green-400 hover:bg-green-50/30">
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
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                Formats acceptés: JPEG, PNG, JPG, GIF, WEBP (max 2MB par fichier, jusqu'à 400 fichiers)
                            </p>
                        </div>
                    </div>

                    {{-- Selected Files List --}}
                    <div id="selectedFilesContainer" class="hidden">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3">Fichiers sélectionnés:</h4>
                        <div id="selectedFilesList" class="space-y-2 max-h-60 overflow-y-auto">
                            <!-- Files will be dynamically added here -->
                        </div>
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
                                    <li><strong>Note:</strong> Si vous importez beaucoup de photos (plus de 50), assurez-vous que votre serveur PHP est configuré avec des valeurs suffisantes pour: <code class="bg-blue-100 px-1 rounded">max_file_uploads</code> (ex: 400), <code class="bg-blue-100 px-1 rounded">upload_max_filesize</code> (ex: 2M), et <code class="bg-blue-100 px-1 rounded">post_max_size</code> (ex: 1000M).</li>
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
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
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
            #photosImportReport .overflow-y-auto {
                scrollbar-width: thin;
                scrollbar-color: #888 #f1f1f1;
            }
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

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && document.getElementById('photosImportReport')) {
                    closePhotosImportReport();
                }
            });

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
            document.getElementById('selectedFilesContainer').classList.add('hidden');
            document.getElementById('selectedFilesList').innerHTML = '';
            document.getElementById('importPhotosSubmitBtn').disabled = false;
            document.getElementById('importPhotosSubmitBtn').textContent = 'Importer les Photos';
            document.getElementById('photosSelectedIndicator').classList.add('hidden');
        }

        document.getElementById('importPhotosModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeImportPhotosModal();
            }
        });

        const photosInput = document.getElementById('photos');
        const selectedFilesContainer = document.getElementById('selectedFilesContainer');
        const selectedFilesList = document.getElementById('selectedFilesList');
        const dropZone = document.getElementById('dropZone');
        const importPhotosForm = document.getElementById('importPhotosForm');
        const importPhotosSubmitBtn = document.getElementById('importPhotosSubmitBtn');
        const totalFilesSelectedInput = document.getElementById('totalFilesSelectedInput');

        photosInput?.addEventListener('change', function(e) {
            handleFiles(e.target.files);
        });

        dropZone?.addEventListener('dragover', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.add('border-green-500', 'bg-green-100/50');
        });

        dropZone?.addEventListener('dragleave', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-green-500', 'bg-green-100/50');
        });

        dropZone?.addEventListener('drop', function(e) {
            e.preventDefault();
            e.stopPropagation();
            this.classList.remove('border-green-500', 'bg-green-100/50');

            const files = e.dataTransfer.files;
            if (files.length > 0) {
                photosInput.files = files;
                handleFiles(files);
            }
        });

        function handleFiles(files) {
            selectedFilesList.innerHTML = '';
            totalFilesSelectedInput.value = files.length;

            if (files.length === 0) {
                selectedFilesContainer.classList.add('hidden');
                return;
            }

            selectedFilesContainer.classList.remove('hidden');

            const maxFiles = 400;
            const maxFileSize = 2 * 1024 * 1024;
            const totalMaxUploadSize = 1000 * 1024 * 1024;
            let totalSize = 0;
            let validFilesCount = 0;

            Array.from(files).forEach(function(file, index) {
                totalSize += file.size;

                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg';

                const fileInfo = document.createElement('div');
                fileInfo.className = 'flex items-center space-x-3 flex-1 min-w-0';

                const icon = document.createElement('div');
                icon.className = 'flex-shrink-0';
                icon.innerHTML = '<svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';

                const details = document.createElement('div');
                details.className = 'flex-1 min-w-0';

                const filename = document.createElement('p');
                filename.className = 'text-sm font-medium text-gray-900 truncate';
                filename.textContent = file.name;

                const filesize = document.createElement('p');
                filesize.className = 'text-xs text-gray-500';
                const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                filesize.textContent = sizeMB + ' MB';

                const matricule = file.name.replace(/\.[^/.]+$/, '');
                const matriculeBadge = document.createElement('span');
                matriculeBadge.className = 'inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 ml-2';
                matriculeBadge.textContent = 'Matricule: ' + matricule;

                details.appendChild(filename);
                details.appendChild(filesize);

                fileInfo.appendChild(icon);
                fileInfo.appendChild(details);

                const status = document.createElement('div');
                status.className = 'flex-shrink-0';

                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

                if (!allowedTypes.includes(file.type)) {
                    status.innerHTML = '<span class="text-red-600 text-xs">Format invalide</span>';
                    fileItem.classList.add('border-red-300', 'bg-red-50');
                } else if (file.size > maxFileSize) {
                    status.innerHTML = '<span class="text-red-600 text-xs">Trop volumineux (>2MB)</span>';
                    fileItem.classList.add('border-red-300', 'bg-red-50');
                } else {
                    status.innerHTML = '<span class="text-green-600 text-xs">✓ Valide</span>';
                    validFilesCount++;
                }

                fileItem.appendChild(fileInfo);
                fileItem.appendChild(matriculeBadge);
                fileItem.appendChild(status);
                selectedFilesList.appendChild(fileItem);
            });

            const photosCountEl = document.getElementById('photosCount');
            const photosTotalSizeEl = document.getElementById('photosTotalSize');
            const photosSelectedIndicator = document.getElementById('photosSelectedIndicator');

            photosCountEl.textContent = `${files.length} fichier(s) sélectionné(s)`;
            photosTotalSizeEl.textContent = `Taille totale: ${(totalSize / (1024 * 1024)).toFixed(2)} MB`;
            photosSelectedIndicator.classList.remove('hidden');

            if (files.length > maxFiles) {
                alert(`Vous avez sélectionné ${files.length} fichiers. La limite est de ${maxFiles} fichiers.`);
                importPhotosSubmitBtn.disabled = true;
            } else if (totalSize > totalMaxUploadSize) {
                alert(`La taille totale des fichiers (${(totalSize / (1024 * 1024)).toFixed(2)} MB) dépasse la limite recommandée de ${totalMaxUploadSize / (1024 * 1024)} MB. L'import pourrait échouer.`);
                importPhotosSubmitBtn.disabled = false;
            } else if (validFilesCount !== files.length) {
                alert('Certains fichiers sélectionnés sont invalides (format ou taille). Veuillez vérifier la liste.');
                importPhotosSubmitBtn.disabled = false;
            } else {
                importPhotosSubmitBtn.disabled = false;
            }
        }

        importPhotosForm?.addEventListener('submit', function(e) {
            const files = photosInput.files;
            const maxFiles = 400;
            const totalMaxUploadSize = 1000 * 1024 * 1024;

            if (files.length === 0) {
                e.preventDefault();
                alert('Veuillez sélectionner au moins une photo à importer.');
                return;
            }

            if (files.length > maxFiles) {
                e.preventDefault();
                alert(`Vous ne pouvez pas importer plus de ${maxFiles} photos à la fois.`);
                return;
            }

            let totalSize = 0;
            for (let i = 0; i < files.length; i++) {
                totalSize += files[i].size;
            }

            if (totalSize > totalMaxUploadSize) {
                if (!confirm(`La taille totale des fichiers (${(totalSize / (1024 * 1024)).toFixed(2)} MB) dépasse la limite recommandée de ${totalMaxUploadSize / (1024 * 1024)} MB. L'import pourrait échouer. Voulez-vous continuer ?`)) {
                    e.preventDefault();
                    return;
                }
            }

            importPhotosSubmitBtn.disabled = true;
            importPhotosSubmitBtn.textContent = 'Import en cours...';
        });
    </script>
@endsection

