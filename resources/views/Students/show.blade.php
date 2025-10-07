@extends('layouts.app')

@section('title', 'Détails de l\'Étudiant - ' . $student->name)
@section('page-subtitle', 'Détails de l\'Étudiant')

@section('content')
    <x-breadcrumb :items="[['label' => 'Étudiants', 'url' => route('students.index')], ['label' => $student->name]]" />

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- Student Photo --}}
            <div class="flex-shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                </div>
            </div>

            {{-- Main Info --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">{{ $student->name }}</h1>
                <p class="text-xl text-indigo-100 mb-2">Matricule: {{ $student->matricule ?? 'N/A' }}</p>
                <p class="text-lg text-indigo-200">
                    {{ $student->classe->label ?? 'N/A' }} •
                    {{ $student->situation === 'NR' ? 'Non Redoublant' : 'Redoublant' }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                @can('generate_certificates')
                    <x-button href="{{ route('students.certificate', $student->id) }}" variant="outline" size="lg"
                        target="_blank"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'
                        class="bg-white text-green-600 hover:bg-gray-100 border-green-500 hover:border-green-600 cursor-pointer">
                        Générer Certificat
                    </x-button>
                @endcan
                @can('generate_cards')
                    <x-button href="{{ route('students.id_card', $student->id) }}" variant="outline" size="lg"
                        target="_blank"
                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>'
                        class="bg-white text-indigo-600 hover:bg-gray-100">
                        Générer Carte d'Étudiant
                    </x-button>
                @endcan
                <x-button href="{{ route('students.index') }}" variant="secondary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>'>
                    Retour
                </x-button>
            </div>
        </div>
    </div>

    {{-- Student Details Card --}}
    <x-card title="Informations Détaillées"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'
        class="mb-8">

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Student ID --}}
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">ID Étudiant</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">#{{ $student->id }}</p>
                <p class="text-sm text-gray-600 mt-1">Référence unique</p>
            </div>

            {{-- School Year --}}
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Année scolaire</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->classe->schoolYear->year ?? 'N/A' }}</p>
            </div>

            {{-- Country --}}
            <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0" />
                            <circle cx="12" cy="10" r="3" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide">Pays de naissance</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $student->place_of_birth ?? 'N/A' }}</p>
            </div>

            {{-- Birth Date --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Date de naissance</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($student->date_of_birth)->age }} ans</p>
            </div>

            {{-- Gender --}}
            <div class="p-6 bg-pink-50 rounded-xl border border-pink-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-pink-700 uppercase tracking-wide">Genre</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}</p>
            </div>

            {{-- Creation Date --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Créé le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $student->created_at->format('H:i') }}</p>
            </div>
        </div>

        {{-- Contact Information --}}
        @if ($student->email || $student->phone || $student->address || $student->parent_name)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span>Informations de Contact</span>
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @if ($student->email)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Email</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->email }}</p>
                        </div>
                    @endif

                    @if ($student->phone)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Téléphone</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->phone }}</p>
                        </div>
                    @endif

                    @if ($student->address)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Adresse</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->address }}</p>
                        </div>
                    @endif

                    @if ($student->parent_name)
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500">Parent/Tuteur</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $student->parent_name }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif


        {{-- Additional Actions --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-600">
                    <p>Dernière mise à jour : {{ $student->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    {{-- Edit Button --}}
                    @can('edit_students')
                        <x-button href="{{ route('students.edit', $student) }}" variant="primary">
                            Modifier l'étudiant
                        </x-button>
                    @endcan

                    {{-- Delete Button --}}
                    @can('delete_students')
                        <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline-block"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">
                                Supprimer
                            </x-button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </x-card>

    <style>
        .detail-card {
            @apply p-6 rounded-2xl shadow-sm border transition-all duration-300 transform hover:scale-[1.01];
        }
    </style>
@endsection
