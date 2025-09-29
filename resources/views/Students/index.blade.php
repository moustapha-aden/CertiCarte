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

            <x-button href="{{ route('students.create') }}" variant="primary" size="md"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                Nouvel Étudiant
            </x-button>
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
                        <label for="class_id" class="text-sm font-medium text-gray-700 whitespace-nowrap">Classe:</label>
                        <select name="class_id" id="class_id" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                            <option value="">Toutes les classes</option>
                            @if (isset($allClasses) && is_iterable($allClasses))
                                @foreach ($allClasses as $id => $label)
                                    <option value="{{ $id }}" {{ request('class_id') == $id ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Gender Filter --}}
                    <div class="flex items-center space-x-2">
                        <label for="gender" class="text-sm font-medium text-gray-700 whitespace-nowrap">Genre:</label>
                        <select name="gender" id="gender" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[120px]">
                            <option value="">Tous</option>
                            <option value="male" {{ request('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                            <option value="female" {{ request('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                        </select>
                    </div>
                </div>

                {{-- Reset Button --}}
                @if (request()->hasAny(['search', 'class_id', 'gender']))
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
            ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()">

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
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $student->gender === 'male' ? 'Masculin' : 'Féminin' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <x-button href="{{ route('students.show', $student) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                    title="Voir les détails" />

                                <x-button href="{{ route('students.edit', $student) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                    title="Modifier" />

                                <form method="POST" action="{{ route('students.destroy', $student) }}"
                                    class="inline-block"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'étudiant {{ $student->name }} ?')">
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

            {{-- Enhanced Pagination --}}
            @if ($students->hasPages())
                <div class="mt-8 bg-gray-50 rounded-lg p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        {{-- Pagination Info --}}
                        <div class="text-sm text-gray-700">
                            Affichage de
                            <span class="font-medium">{{ $students->firstItem() }}</span>
                            à
                            <span class="font-medium">{{ $students->lastItem() }}</span>
                            sur
                            <span class="font-medium">{{ $students->total() }}</span>
                            résultats
                        </div>

                        {{-- Pagination Links --}}
                        <div class="flex justify-center sm:justify-end">
                            {{ $students->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @endif
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
                    @if (request()->hasAny(['search', 'class_id', 'year']))
                        Aucun étudiant trouvé
                    @else
                        Aucun étudiant enregistré
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    @if (request()->hasAny(['search', 'class_id', 'year']))
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
@endsection
