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
                    <label for="year_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Année scolaire:</label>
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
                
                {{-- Add Class Button --}}
                <x-button href="{{ route('classes.create') }}" variant="primary" size="md"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                    Nouvelle Classe
                </x-button>
            </div>
        </div>

        {{-- Statistics Section --}}
        @if (request('year_id'))
            @php
                $selectedYear = $schoolYears->firstWhere('id', request('year_id'));
                $totalStudents = $classes->sum(function ($classe) {
                    return $classe->students->count();
                });
            @endphp
            <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-indigo-800">{{ $selectedYear->year ?? 'Année sélectionnée' }}</h3>
                        <p class="text-sm text-indigo-600">{{ $classes->count() }} classe(s) • {{ $totalStudents }} élève(s)</p>
                    </div>
                    <svg class="w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                    </svg>
                </div>
            </div>
        @endif

        {{-- Classes Table Section --}}
        @if ($classes->count() > 0)
            <x-table :headers="[
                ['label' => 'Nom de la Classe'],
                ['label' => 'Année Scolaire'],
                ['label' => 'Nombre d\'Élèves'],
                ['label' => 'Date de Création'],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]">
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
                                <x-button href="{{ route('classes.show', $classe) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                    title="Voir les détails" />

                                <x-button href="{{ route('classes.edit', $classe) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                    title="Modifier" />

                                <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la classe {{ $classe->label }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="ghost" size="sm"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                        title="Supprimer" class="text-red-600 hover:text-red-800 hover:bg-red-50" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>

            {{-- Pagination --}}
            @if ($classes->hasPages())
                <div class="mt-8">
                    {{ $classes->appends(request()->query())->links() }}
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
                <x-button href="{{ route('classes.create') }}" variant="primary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                    Créer une nouvelle classe
                </x-button>
            </div>
        @endif
    </x-card>
@endsection