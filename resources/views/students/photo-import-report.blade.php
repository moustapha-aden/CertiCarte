@extends('layouts.app')

@section('title', 'Rapport d\'Import des Photos')
@section('page-subtitle', 'Rapport d\'Import des Photos d\'Étudiants')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Étudiants', 'route' => route('students.index')],
        ['label' => 'Rapport d\'Import'],
    ]" />

    {{-- Report Header Card --}}
    <x-card class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Rapport d'Import des Photos</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Import effectué le {{ \Carbon\Carbon::parse($report['created_at'])->format('d/m/Y à H:i') }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                @if($backRouteParams)
                    <x-button href="{{ route($backRoute, $backRouteParams) }}" variant="secondary" size="md">
                        {{ $backLabel }}
                    </x-button>
                @else
                    <x-button href="{{ route($backRoute) }}" variant="secondary" size="md">
                        {{ $backLabel }}
                    </x-button>
                @endif
                <x-button href="{{ route('students.index') }}" variant="primary" size="md"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>'>
                    Liste des Étudiants
                </x-button>
            </div>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            {{-- Fichiers Reçus --}}
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide mb-1">Fichiers Reçus</h3>
                <p class="text-3xl font-bold text-blue-900">{{ $report['received'] }}</p>
            </div>

            {{-- Photos Importées --}}
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide mb-1">Photos Importées</h3>
                <p class="text-3xl font-bold text-green-900">{{ $report['imported'] }}</p>
                @if($report['replaced'] > 0)
                    <p class="text-xs text-green-700 mt-1">{{ $report['replaced'] }} remplacée(s)</p>
                @endif
            </div>

            {{-- Photos Non Associées --}}
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 border border-red-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-red-700 uppercase tracking-wide mb-1">Non Associées</h3>
                <p class="text-3xl font-bold text-red-900">{{ count($report['not_found']) }}</p>
            </div>

            {{-- Erreurs --}}
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-6 border border-orange-200 shadow-sm">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-semibold text-orange-700 uppercase tracking-wide mb-1">Erreurs</h3>
                <p class="text-3xl font-bold text-orange-900">{{ count($report['errors']) }}</p>
            </div>
        </div>

        {{-- PHP Limit Warning --}}
        @if (isset($report['max_file_uploads']) && $report['max_file_uploads'] > 0 && $report['received'] >= $report['max_file_uploads'])
            <div class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5 mr-3" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-yellow-800 mb-1">Avertissement de Limite PHP</h3>
                        <p class="text-sm text-yellow-700">
                            Le nombre de fichiers reçus ({{ $report['received'] }}) atteint la limite PHP
                            <code class="bg-yellow-100 px-1.5 py-0.5 rounded text-xs font-mono">max_file_uploads</code>
                            ({{ $report['max_file_uploads'] }}). Certains fichiers peuvent ne pas avoir été envoyés.
                            Consultez la documentation pour augmenter cette limite.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Success Message --}}
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5 mr-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif
    </x-card>

    {{-- Not Found Photos Section --}}
    @if (!empty($report['not_found']))
        <x-card title="Photos Non Associées" class="mb-8"
            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'>
            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    Les {{ count($report['not_found']) }} photos suivantes n'ont pas pu être associées à des étudiants car les matricules n'ont pas été trouvés dans la base de données.
                </p>
            </div>

            {{-- Search Bar --}}
            @if (count($report['not_found']) > 20)
                <div class="mb-4">
                    <div class="relative max-w-md">
                        <input type="text" id="searchNotFoundPhotos" placeholder="Rechercher un matricule..."
                            class="w-full px-4 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
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
                    <p class="text-xs text-gray-500 mt-2">
                        <span id="visibleNotFoundCount">{{ count($report['not_found']) }}</span> / {{ count($report['not_found']) }} résultats affichés
                    </p>
                </div>
            @endif

            {{-- Not Found Files List --}}
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3" id="notFoundPhotosList">
                    @foreach ($report['not_found'] as $filename)
                        <div class="notFoundItem bg-white border border-red-200 rounded-lg p-3 flex items-start space-x-2 hover:bg-red-50 hover:shadow-md transition-all">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <code
                                class="text-xs text-red-800 break-all bg-red-50 px-2 py-1 rounded flex-1 font-mono">{{ $filename }}</code>
                        </div>
                    @endforeach
                </div>
            </div>
        </x-card>
    @endif

    {{-- Errors Section --}}
    @if (!empty($report['errors']))
        <x-card title="Erreurs Techniques" class="mb-8"
            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'>
            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    {{ count($report['errors']) }} erreur(s) technique(s) survenue(s) lors de l'import.
                </p>
            </div>

            <div class="space-y-3">
                @foreach ($report['errors'] as $error)
                    <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5 mr-3" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm text-red-800 font-medium">{{ $error }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-card>
    @endif

    {{-- Success Details Section (Optional) --}}
    @if (!empty($report['details']) && $report['imported'] > 0)
        <x-card title="Détails des Photos Importées" class="mb-8"
            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'>
            <div class="mb-4">
                <p class="text-sm text-gray-600">
                    {{ $report['imported'] }} photo(s) importée(s) avec succès.
                </p>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 max-h-96 overflow-y-auto custom-scrollbar">
                    @foreach ($report['details'] as $detail)
                        @if (isset($detail['status']) && $detail['status'] === 'success')
                            <div class="bg-white border border-green-200 rounded-lg p-3 flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-medium text-gray-900 truncate" title="{{ $detail['filename'] }}">
                                        {{ $detail['filename'] }}
                                    </p>
                                    <p class="text-xs text-gray-600 font-mono">{{ $detail['matricule'] }}</p>
                                    @if (isset($detail['replaced']) && $detail['replaced'])
                                        <span class="text-xs text-blue-600 font-medium">Remplacée</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </x-card>
    @endif
@endsection

@push('scripts')
    <style>
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
    </style>

    @if (!empty($report['not_found']) && count($report['not_found']) > 20)
        <script>
            // Search functionality for not found photos
            const searchNotFoundInput = document.getElementById('searchNotFoundPhotos');
            const clearSearchBtn = document.getElementById('clearSearchNotFoundPhotos');
            const visibleCountSpan = document.getElementById('visibleNotFoundCount');

            if (searchNotFoundInput) {
                searchNotFoundInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase().trim();
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

                    if (visibleCountSpan) {
                        visibleCountSpan.textContent = visibleCount;
                    }

                    if (clearSearchBtn) {
                        if (searchTerm) {
                            clearSearchBtn.classList.remove('hidden');
                        } else {
                            clearSearchBtn.classList.add('hidden');
                        }
                    }
                });

                if (clearSearchBtn) {
                    clearSearchBtn.addEventListener('click', function() {
                        searchNotFoundInput.value = '';
                        searchNotFoundInput.dispatchEvent(new Event('input'));
                    });
                }
            }
        </script>
    @endif
@endpush

