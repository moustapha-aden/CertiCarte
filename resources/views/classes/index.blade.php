@extends('layouts.app')

@section('title', 'Gestion des Classes')
@section('page-subtitle', 'Gestion des Classes')

@section('content')
    <x-breadcrumb :items="[['label' => 'Classes']]" />

    {{-- Single Card: Complete Classes Management --}}
    <x-card class="mb-8">
        {{-- Card Header: Title, School Year Filter, and Add Button --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
            {{-- Title --}}
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800">Catalogue des Classes</h1>
            </div>

            {{-- School Year Filter and Add Button --}}
            <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                {{-- School Year Filter --}}
                <form method="GET" action="{{ route('classes.index') }}" class="flex items-center space-x-2">
                    <label for="year_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Année
                        scolaire:</label>
                    <select name="year_id" id="year_id" onchange="this.form.submit()"
                        class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                        <option value="">Toutes les années</option>
                        @if (isset($schoolYears) && is_iterable($schoolYears))
                            @foreach ($schoolYears as $year)
                                <option value="{{ $year->id }}" {{ request('year_id') == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </form>

                {{-- Import Photos Button --}}
                @can('import_students')
                    <x-button type="button" onclick="openImportPhotosModal()" variant="success" size="md"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>'>
                        Importer des Photos
                    </x-button>
                @endcan

                {{-- Add Class Button --}}
                @can('create_classes')
                    <x-button href="{{ route('classes.create') }}" variant="primary" size="md"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                        Nouvelle Classe
                    </x-button>
                @endcan
            </div>
        </div>

        {{-- Classes Table Section --}}
        @if ($classes->count() > 0)
            <x-table :headers="[
                ['field' => 'label', 'label' => 'Nom de la Classe', 'sortable' => true, 'route' => 'classes.index'],
                ['label' => 'Année Scolaire'],
                [
                    'field' => 'students_count',
                    'label' => 'Nombre d\'Élèves',
                    'sortable' => true,
                    'route' => 'classes.index',
                ],
                [
                    'field' => 'created_at',
                    'label' => 'Date de Création',
                    'sortable' => true,
                    'route' => 'classes.index',
                ],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$classes"
                :itemLabel="'classes'">

                @foreach ($classes as $classe)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        {{-- Class Name --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-lg font-semibold text-gray-900">{{ $classe->label }}</div>
                                    <div class="text-sm text-gray-500">ID: #{{ $classe->id }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- School Year --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $classe->schoolYear->year ?? 'N/A' }}</div>
                        </td>

                        {{-- Student Count --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-2xl font-bold text-indigo-600">{{ $classe->students->count() }}</span>
                                <span
                                    class="text-sm text-gray-500 ml-2">élève{{ $classe->students->count() > 1 ? 's' : '' }}</span>
                            </div>
                        </td>

                        {{-- Creation Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div>{{ $classe->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $classe->created_at->format('H:i') }}</div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                {{-- View Button --}}
                                @can('view_classes')
                                    <x-button href="{{ route('classes.show', $classe) }}" variant="ghost" size="sm"
                                        title="Voir les détails"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 border-blue-200 hover:border-blue-300" />
                                @endcan

                                {{-- Edit Button --}}
                                @can('edit_classes')
                                    <x-button href="{{ route('classes.edit', $classe) }}" variant="ghost" size="sm"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                        title="Modifier"
                                        class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border-indigo-200 hover:border-indigo-300" />
                                @endcan

                                {{-- Delete Button --}}
                                @can('delete_classes')
                                    <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la classe {{ $classe->label }} ?')">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="ghost" size="sm"
                                            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                            title="Supprimer" class="text-red-600 hover:text-red-800 hover:bg-red-50" />
                                    </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>

            {{-- Pagination --}}
            @if ($classes->hasPages())
                <div class="mt-8">
                    <x-pagination :paginator="$classes" :itemLabel="'classes'" />
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    @if (request()->hasAny(['class_search', 'year_id']))
                        Aucune classe trouvée
                    @else
                        Aucune classe enregistrée
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    @if (request()->hasAny(['class_search', 'year_id']))
                        Aucune classe ne correspond aux critères de recherche.
                    @else
                        Commencez par créer votre première classe.
                    @endif
                </p>
                @can('create_classes')
                    <x-button href="{{ route('classes.create') }}" variant="primary" size="lg"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                        Créer une nouvelle classe
                    </x-button>
                @endcan
            </div>
        @endif
    </x-card>

    {{-- Import Photos Modal --}}
    @can('import_students')
        <div id="importPhotosModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
            onclick="if(event.target === this) closeImportPhotosModal()">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">Importer des Photos d'Étudiants</h2>
                    <button type="button" onclick="closeImportPhotosModal()"
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body -- Scrollable --}}
                <form id="importPhotosForm" method="POST" action="{{ route('students.import-photos') }}"
                    enctype="multipart/form-data" class="flex-1 flex flex-col overflow-hidden">
                    @csrf

                    <div class="flex-1 overflow-y-auto p-4 sm:p-6">
                        {{-- Error Messages --}}
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <h3 class="text-sm font-medium text-red-800 mb-2">Erreurs de validation :</h3>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Success Message --}}
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                                </div>
                            </div>
                        @endif

                        {{-- General Error Message --}}
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                                </div>
                            </div>
                        @endif
                        {{-- Instructions --}}
                        <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start space-x-2 sm:space-x-3">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="flex-1 text-xs sm:text-sm text-blue-800">
                                    <p class="font-semibold mb-1 sm:mb-2">Instructions importantes :</p>
                                    <ul class="list-disc list-inside space-y-0.5 sm:space-y-1 text-blue-700">
                                        <li>Les photos doivent être nommées avec le <strong>matricule</strong> de l'étudiant
                                            (ex: <code class="bg-blue-100 px-1 rounded text-xs">12345.jpg</code>)</li>
                                        <li>Formats acceptés : JPEG, PNG, JPG, GIF, WEBP</li>
                                        <li>Taille maximale par photo : 2MB</li>
                                        <li>Maximum : 400 photos par import</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Drag & Drop Zone --}}
                        <div id="dropZone"
                            class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 md:p-8 text-center hover:border-indigo-500 transition-colors cursor-pointer mb-4">
                            <input type="file" id="photoInput" name="photos[]" multiple accept="image/*"
                                class="hidden">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 text-gray-400 mx-auto mb-2 sm:mb-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p class="text-sm sm:text-base text-gray-600 mb-1 sm:mb-2">
                                <span class="text-indigo-600 font-semibold">Cliquez pour sélectionner</span> ou glissez-déposez
                                les photos ici
                            </p>
                            <p class="text-xs sm:text-sm text-gray-500">Sélectionnez plusieurs fichiers à la fois</p>
                        </div>

                        {{-- Selected Files List -- Scrollable avec meilleure gestion --}}
                        <div id="selectedFiles" class="hidden mb-4 border border-gray-200 rounded-lg overflow-hidden flex flex-col" style="max-height: calc(95vh - 450px); min-height: 150px;">
                            <div class="flex items-center justify-between p-3 sm:p-4 border-b border-gray-200 bg-gray-50 flex-shrink-0">
                                <h3 class="text-sm sm:text-base font-semibold text-gray-900">
                                    Fichiers sélectionnés (<span id="fileCount">0</span>)
                                </h3>
                                <button type="button" onclick="clearAllFiles()" id="clearAllBtn" class="text-xs sm:text-sm text-red-600 hover:text-red-800 font-medium">
                                    Tout effacer
                                </button>
                            </div>
                            <div id="fileList" class="flex-1 overflow-y-auto p-2 sm:p-4 custom-scrollbar">
                                {{-- Les fichiers seront ajoutés ici par JavaScript --}}
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer -- Sticky --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 sm:gap-3 p-4 sm:p-6 border-t border-gray-200 bg-white flex-shrink-0">
                        <button type="button" onclick="closeImportPhotosModal()"
                            class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Annuler
                        </button>
                        <button type="submit" id="submitBtn"
                            class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                            <span id="submitText">Importer (<span id="submitFileCount">0</span>)</span>
                            <span id="submitLoading" class="hidden">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                                Import en cours...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Import Report Modal --}}
        @if (session('photos_import_report'))
            @php
                $report = session('photos_import_report');
            @endphp
            <div id="photosImportReport" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                onclick="if(event.target === this) closeImportReportModal()">
                <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                    onclick="event.stopPropagation()">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900">Rapport d'import des photos</h2>
                        <button type="button" onclick="closeImportReportModal()"
                            class="text-gray-400 hover:text-gray-600 transition-colors">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-4 sm:p-6">
                        {{-- Summary --}}
                        <div class="mb-4 sm:mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <h3 class="text-base sm:text-lg font-semibold text-green-900 mb-2">Résumé</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-sm">
                                <div>
                                    <span class="text-green-700 font-medium">Fichiers reçus:</span>
                                    <span class="text-green-900 font-bold ml-2">{{ $report['received'] }}</span>
                                </div>
                                <div>
                                    <span class="text-green-700 font-medium">Photos importées:</span>
                                    <span class="text-green-900 font-bold ml-2">{{ $report['imported'] }}</span>
                                </div>
                                @if ($report['replaced'] > 0)
                                    <div>
                                        <span class="text-green-700 font-medium">Photos remplacées:</span>
                                        <span class="text-green-900 font-bold ml-2">{{ $report['replaced'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- PHP Limit Warning --}}
                        @if (isset($report['max_file_uploads']) && $report['max_file_uploads'] > 0 && $report['received'] >= $report['max_file_uploads'])
                            <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                        </path>
                                    </svg>
                                    <div class="flex-1 text-sm text-yellow-800">
                                        <p class="font-semibold mb-1">Avertissement</p>
                                        <p>Le nombre de fichiers reçus atteint la limite PHP <code
                                                class="bg-yellow-100 px-1 rounded">max_file_uploads</code>
                                            ({{ $report['max_file_uploads'] }}). Certains fichiers peuvent ne pas avoir été
                                            envoyés. Consultez la documentation pour augmenter cette limite.</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Not Found Photos --}}
                        @if (!empty($report['not_found']))
                            <div class="mb-4 sm:mb-6">
                                <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900">
                                        Photos non associées (<span id="visibleNotFoundCount">{{ count($report['not_found']) }}</span>
                                        / {{ count($report['not_found']) }})
                                    </h4>
                                </div>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                                    @if (count($report['not_found']) > 20)
                                        <div class="relative mb-3">
                                            <input type="text" id="searchNotFoundPhotos"
                                                placeholder="Rechercher un matricule..."
                                                class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <button type="button" id="clearSearchNotFoundPhotos"
                                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 hidden">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                    <p class="text-xs sm:text-sm text-red-700 mb-3 sm:mb-4">
                                        Les matricules suivants n'ont pas été trouvés dans la base de données:
                                    </p>
                                    <div class="max-h-96 sm:max-h-[500px] overflow-y-auto custom-scrollbar">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3"
                                            id="notFoundPhotosList">
                                            @foreach ($report['not_found'] as $filename)
                                                <div class="notFoundItem bg-white border border-red-200 rounded-md p-2 sm:p-3 flex items-start space-x-2 hover:bg-red-50 transition-colors">
                                                    <svg class="w-4 h-4 text-red-500 flex-shrink-0 mt-0.5" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <code
                                                        class="text-xs sm:text-sm text-red-800 break-all bg-red-50 px-2 py-1 rounded flex-1 font-mono">{{ $filename }}</code>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @if (count($report['not_found']) > 20)
                                        <p class="text-xs text-red-600 mt-3 italic" id="notFoundScrollHint">
                                            Utilisez la recherche pour filtrer les résultats ou faites défiler pour voir tous
                                            les fichiers.
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- Errors --}}
                        @if (!empty($report['errors']))
                            <div class="mb-4 sm:mb-6">
                                <div class="flex items-center space-x-2 mb-3 sm:mb-4">
                                    <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h4 class="text-base sm:text-lg font-semibold text-gray-900">
                                        Erreurs ({{ count($report['errors']) }})
                                    </h4>
                                </div>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4">
                                    <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-2">
                                        @foreach ($report['errors'] as $error)
                                            <div class="bg-white border border-red-200 rounded-md p-2 sm:p-3 text-xs sm:text-sm text-red-800">
                                                {{ $error }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Modal Footer --}}
                        <div class="flex justify-end pt-4 border-t border-gray-200">
                            <button type="button" onclick="closeImportReportModal()"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors">
                                Fermer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    <script>
        @can('import_students')
            // Import Photos Modal Functions
            let selectedFiles = [];

            function openImportPhotosModal() {
                const modal = document.getElementById('importPhotosModal');
                if (modal) {
                    // Reset form state first
                    resetImportForm();

                    // Show modal
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    // Scroll to top of modal body
                    const modalBody = modal.querySelector('.flex-1.overflow-y-auto');
                    if (modalBody) {
                        modalBody.scrollTop = 0;
                    }
                }
            }

            function closeImportPhotosModal() {
                const modal = document.getElementById('importPhotosModal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
                resetImportForm();
            }

            function resetImportForm() {
                selectedFiles = [];
                const photoInput = document.getElementById('photoInput');
                if (photoInput) {
                    photoInput.value = '';
                }

                const selectedFilesDiv = document.getElementById('selectedFiles');
                if (selectedFilesDiv) {
                    selectedFilesDiv.classList.add('hidden');
                }

                const fileList = document.getElementById('fileList');
                if (fileList) {
                    fileList.innerHTML = '';
                }

                const fileCount = document.getElementById('fileCount');
                if (fileCount) {
                    fileCount.textContent = '0';
                }

                const submitFileCount = document.getElementById('submitFileCount');
                if (submitFileCount) {
                    submitFileCount.textContent = '0';
                }

                const submitBtn = document.getElementById('submitBtn');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }

                const submitText = document.getElementById('submitText');
                if (submitText) {
                    submitText.classList.remove('hidden');
                }

                const submitLoading = document.getElementById('submitLoading');
                if (submitLoading) {
                    submitLoading.classList.add('hidden');
                }

                // Hide any error messages
                hideError();

                // Reset form
                const form = document.getElementById('importPhotosForm');
                if (form) {
                    form.reset();
                }
            }

            // Drag & Drop handlers
            const dropZone = document.getElementById('dropZone');
            const photoInput = document.getElementById('photoInput');

            if (dropZone && photoInput) {
                // Click to select files
                dropZone.addEventListener('click', () => {
                    photoInput.click();
                });

                // Drag & drop events
                dropZone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-indigo-500', 'bg-indigo-50');
                });

                dropZone.addEventListener('dragleave', () => {
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
                });

                dropZone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-indigo-500', 'bg-indigo-50');
                    if (e.dataTransfer.files.length > 0) {
                        handleFiles(Array.from(e.dataTransfer.files));
                    }
                });

                // File input change
                photoInput.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) {
                        handleFiles(Array.from(e.target.files));
                    }
                });
            }

            function handleFiles(files) {
                const validFiles = [];
                const maxSize = 2 * 1024 * 1024; // 2MB
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

                files.forEach(file => {
                    if (!validTypes.includes(file.type)) {
                        alert(`Le fichier "${file.name}" n'est pas une image valide. Formats acceptés: JPEG, PNG, JPG, GIF, WEBP`);
                        return;
                    }
                    if (file.size > maxSize) {
                        alert(`Le fichier "${file.name}" est trop volumineux (max 2MB)`);
                        return;
                    }
                    validFiles.push(file);
                });

                // Add valid files to selection
                selectedFiles = [...selectedFiles, ...validFiles];

                // Update file input (create new input with all selected files)
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                photoInput.files = dataTransfer.files;

                updateFileList();
            }

            function updateFileList() {
                const fileListDiv = document.getElementById('fileList');
                const selectedFilesDiv = document.getElementById('selectedFiles');
                const fileCountSpan = document.getElementById('fileCount');
                const submitFileCountSpan = document.getElementById('submitFileCount');
                const clearAllBtn = document.getElementById('clearAllBtn');

                if (selectedFiles.length === 0) {
                    selectedFilesDiv.classList.add('hidden');
                    if (submitFileCountSpan) submitFileCountSpan.textContent = '0';
                    return;
                }

                selectedFilesDiv.classList.remove('hidden');
                fileCountSpan.textContent = selectedFiles.length;
                if (submitFileCountSpan) submitFileCountSpan.textContent = selectedFiles.length;
                if (clearAllBtn) clearAllBtn.style.display = selectedFiles.length > 0 ? 'block' : 'none';

                // Utiliser une grille responsive pour un meilleur affichage
                fileListDiv.innerHTML = `
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 sm:gap-3">
                        ${selectedFiles.map((file, index) => {
                            const fileSize = (file.size / 1024).toFixed(2);
                            const fileName = file.name;
                            const matricule = fileName.replace(/\.[^/.]+$/, ''); // Remove extension
                            const isValidSize = file.size <= 2 * 1024 * 1024; // 2MB
                            const fileExtension = fileName.split('.').pop().toLowerCase();
                            const isValidFormat = ['jpeg', 'png', 'jpg', 'gif', 'webp'].includes(fileExtension);

                            return `
                                <div class="bg-white border ${isValidSize && isValidFormat ? 'border-gray-200' : 'border-red-300'} rounded-lg p-2 sm:p-3 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2 mb-1">
                                                ${isValidSize && isValidFormat
                                                    ? '<svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                                                    : '<svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                                                }
                                                <div class="text-xs sm:text-sm font-medium text-gray-900 truncate" title="${fileName}">${fileName}</div>
                                            </div>
                                            <div class="text-xs text-gray-600 mb-1">
                                                <span class="font-mono font-semibold text-indigo-600">${matricule}</span>
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <span>${fileSize} KB</span>
                                                ${!isValidSize ? '<span class="text-red-600 font-semibold">Trop volumineux</span>' : ''}
                                                ${!isValidFormat ? '<span class="text-red-600 font-semibold">Format invalide</span>' : ''}
                                            </div>
                                        </div>
                                        <button type="button" onclick="removeFile(${index})"
                                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-1 rounded transition-colors flex-shrink-0"
                                            title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                `;
            }

            function clearAllFiles() {
                if (confirm(`Êtes-vous sûr de vouloir supprimer tous les ${selectedFiles.length} fichier(s) sélectionné(s) ?`)) {
                    selectedFiles = [];
                    const photoInput = document.getElementById('photoInput');
                    if (photoInput) {
                        photoInput.value = '';
                    }
                    updateFileList();
                }
            }

            function removeFile(index) {
                selectedFiles.splice(index, 1);

                // Update file input
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                photoInput.files = dataTransfer.files;

                updateFileList();
            }

            // Form submission with better error handling
            const importPhotosForm = document.getElementById('importPhotosForm');
            if (importPhotosForm) {
                importPhotosForm.addEventListener('submit', function(e) {
                    // Prevent default only if validation fails
                    if (selectedFiles.length === 0) {
                        e.preventDefault();
                        showError('Veuillez sélectionner au moins une photo à importer.');
                        return false;
                    }

                    // Validate files before submission
                    const invalidFiles = selectedFiles.filter(file => {
                        const maxSize = 2 * 1024 * 1024; // 2MB
                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                        return !validTypes.includes(file.type) || file.size > maxSize;
                    });

                    if (invalidFiles.length > 0) {
                        e.preventDefault();
                        showError(`${invalidFiles.length} fichier(s) invalide(s). Veuillez retirer les fichiers invalides avant de continuer.`);
                        return false;
                    }

                    // Ensure all files are properly set in the input
                    const photoInput = document.getElementById('photoInput');
                    if (photoInput && photoInput.files.length !== selectedFiles.length) {
                        // Re-sync files
                        const dataTransfer = new DataTransfer();
                        selectedFiles.forEach(file => dataTransfer.items.add(file));
                        photoInput.files = dataTransfer.files;
                    }

                    const submitBtn = document.getElementById('submitBtn');
                    const submitText = document.getElementById('submitText');
                    const submitLoading = document.getElementById('submitLoading');

                    // Disable form elements during submission
                    submitBtn.disabled = true;
                    submitText.classList.add('hidden');
                    submitLoading.classList.remove('hidden');

                    // Hide any previous error messages
                    hideError();

                    // Allow form to submit normally (Laravel will handle the redirect)
                    return true;
                });
            }

            // Error display functions
            function showError(message) {
                hideError();
                const errorDiv = document.createElement('div');
                errorDiv.id = 'importErrorAlert';
                errorDiv.className = 'mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start space-x-3';
                errorDiv.innerHTML = `
                    <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-red-800">${message}</p>
                    </div>
                    <button type="button" onclick="hideError()" class="text-red-600 hover:text-red-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                `;

                const formBody = importPhotosForm.querySelector('.flex-1.overflow-y-auto');
                if (formBody) {
                    formBody.insertBefore(errorDiv, formBody.firstChild);
                    formBody.scrollTop = 0;
                }
            }

            function hideError() {
                const errorAlert = document.getElementById('importErrorAlert');
                if (errorAlert) {
                    errorAlert.remove();
                }
            }

            // Import Report Modal Functions
            function closeImportReportModal() {
                const modal = document.getElementById('photosImportReport');
                if (modal) {
                    modal.classList.add('hidden');
                    // Redirect to clear session flash data
                    window.location.href = '{{ route("classes.index") }}';
                }
            }

            // Search functionality for not found photos
            const searchNotFoundInput = document.getElementById('searchNotFoundPhotos');
            if (searchNotFoundInput) {
                searchNotFoundInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
                    const clearBtn = document.getElementById('clearSearchNotFoundPhotos');
                    const items = document.querySelectorAll('.notFoundItem');
                    let visibleCount = 0;

                    items.forEach(item => {
                        const text = item.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    document.getElementById('visibleNotFoundCount').textContent = visibleCount;

                    if (searchTerm) {
                        clearBtn.classList.remove('hidden');
                    } else {
                        clearBtn.classList.add('hidden');
                    }
                });

                const clearBtn = document.getElementById('clearSearchNotFoundPhotos');
                if (clearBtn) {
                    clearBtn.addEventListener('click', function() {
                        searchNotFoundInput.value = '';
                        searchNotFoundInput.dispatchEvent(new Event('input'));
                    });
                }
            }

            // Custom scrollbar styles
            const style = document.createElement('style');
            style.textContent = `
                .custom-scrollbar::-webkit-scrollbar {
                    width: 8px;
                    height: 8px;
                }
                .custom-scrollbar::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb {
                    background: #888;
                    border-radius: 4px;
                }
                .custom-scrollbar::-webkit-scrollbar-thumb:hover {
                    background: #555;
                }
                /* Amélioration pour mobile */
                @media (max-width: 640px) {
                    #importPhotosModal .bg-white {
                        height: 100vh !important;
                        max-height: 100vh !important;
                        border-radius: 0 !important;
                    }
                }
            `;
            document.head.appendChild(style);

            // Auto-open import report modal if report exists
            @if (session('photos_import_report'))
                document.addEventListener('DOMContentLoaded', function() {
                    const reportModal = document.getElementById('photosImportReport');
                    if (reportModal) {
                        reportModal.classList.remove('hidden');
                    }
                });
            @endif
        @endcan
    </script>

@endsection
