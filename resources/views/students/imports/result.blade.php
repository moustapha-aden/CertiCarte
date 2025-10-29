@extends('layouts.app')

@section('title', 'Résultats de l\'Import')
@section('page-subtitle', 'Résultats de l\'Import d\'Étudiants')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Étudiants', 'route' => route('students.index')],
        ['label' => 'Importer', 'route' => route('students-import.index')],
        ['label' => 'Résultats'],
    ]" />

    {{-- Import Summary Card --}}
    <x-card class="mb-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Résultats de l'Import</h1>
                    <p class="text-sm text-gray-600">{{ $import->filename }}</p>
                </div>
            </div>
            <div class="flex gap-3">
                <x-button href="{{ route('students-import.index') }}" variant="secondary" size="md">
                    Retour à l'Import
                </x-button>
                <x-button href="{{ route('students.index') }}" variant="primary" size="md">
                    Retour aux Étudiants
                </x-button>
            </div>
        </div>

        {{-- Import Details Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

            {{-- Total Rows --}}
            <x-stat-card label="Imports Totaux" :value="$import->total_rows" color="blue" :icon="svg('sigma')" />

            {{-- Success Count --}}
            <x-stat-card label="Imports Réussis" :value="$import->success_count" color="green" :icon="svg('success')" />

            {{-- Failed Count --}}
            <x-stat-card label="Imports Échoués" :value="$import->failed_count" color="red" :icon="svg('error')" />

            {{-- Import Info --}}
            <x-stat-card label="Importé Par :" :value="$import->user->name" color="gray" :icon="svg('user')" />
        </div>

        {{-- Status Badge and Duration --}}
        {{-- <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span
                    class="px-4 py-2 rounded-full text-sm font-medium
                    @if ($import->isCompleted()) bg-green-100 text-green-800 border border-green-200
                    @elseif($import->isFailed()) bg-red-100 text-red-800 border border-red-200
                    @else bg-yellow-100 text-yellow-800 border border-yellow-200 @endif">
                    @if ($import->isCompleted())
                        ✓ Terminé
                    @elseif($import->isFailed())
                        ✗ Échec
                    @else
                        ⏳ En cours
                    @endif
                </span>
                @if ($import->duration)
                    <p class="text-gray-600 flex items-center">
                        <span class="size-4">{!! svg('clock') !!}</span>
                        <span class="ml-2 text-md">Durée: {{ $import->duration }}</span>
                    </p>
                @endif
            </div>
        </div> --}}
    </x-card>

    {{-- Failed Rows --}}
    @if ($import->failed_count > 0)
        <x-card>
            <div class="mb-6">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Lignes Non Importées ({{ $import->failed_count }})</h2>
                <p class="text-sm text-gray-600">Les lignes suivantes n'ont pas pu être importées. Veuillez corriger les
                    erreurs et réessayer.</p>
            </div>

            {{-- Errors Table --}}
            <x-table :headers="[
                [
                    'field' => 'row_number',
                    'label' => 'Ligne',
                    'sortable' => true,
                    'route' => 'students-import.show',
                    'routeParams' => ['id' => $import->id],
                ],
                [
                    'field' => 'error_type',
                    'label' => 'Type d\'Erreur',
                    'sortable' => true,
                    'route' => 'students-import.show',
                    'routeParams' => ['id' => $import->id],
                ],
                [
                    'field' => 'error_message',
                    'label' => 'Message d\'Erreur',
                    'sortable' => true,
                    'route' => 'students-import.show',
                    'routeParams' => ['id' => $import->id],
                ],
                ['label' => 'Action', 'class' => 'text-right'],
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$errors"
                :itemLabel="'erreurs'">
                @foreach ($errors as $error)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $error->row_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $error->badge_color }}-100 text-{{ $error->badge_color }}-800">
                                {{ ucfirst(str_replace('_', ' ', $error->error_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $error->error_message }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                            <button onclick="toggleRowData({{ $error->id }})"
                                class="text-indigo-600 hover:text-indigo-900 font-medium">
                                <span id="toggle-text-{{ $error->id }}">Afficher</span>
                            </button>
                        </td>
                    </tr>
                    {{-- Expandable Row Data --}}
                    <tr id="row-data-{{ $error->id }}" class="hidden">
                        <td colspan="4" class="px-6 py-4 bg-gray-50">
                            <div class="mb-2">
                                <span class="text-sm font-medium text-gray-700">Données de la ligne:</span>
                            </div>
                            <pre class="bg-gray-900 text-green-400 p-4 rounded-lg text-sm overflow-x-auto">{{ $error->formatted_row_data }}</pre>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>
    @endif
@endsection

@push('scripts')
    <script>
        function toggleRowData(errorId) {
            const row = document.getElementById('row-data-' + errorId);
            const toggleText = document.getElementById('toggle-text-' + errorId);

            if (row.classList.contains('hidden')) {
                row.classList.remove('hidden');
                toggleText.textContent = 'Masquer';
            } else {
                row.classList.add('hidden');
                toggleText.textContent = 'Afficher';
            }
        }
    </script>
@endpush
