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
                    {{ $student->classe->label ?? 'Classe non assignée' }} • {{ $student->schoolYear->year ?? 'N/A' }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <x-button href="{{ route('students.edit', $student) }}" variant="outline" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    class="bg-white text-indigo-600 hover:bg-gray-100">
                    Modifier
                </x-button>
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
            {{-- Student Name --}}
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Nom complet</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->name }}</p>
            </div>

            {{-- Matricule --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Matricule</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->matricule ?? 'N/A' }}</p>
            </div>

            {{-- Class --}}
            <div class="p-6 bg-indigo-50 rounded-xl border border-indigo-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Classe</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->classe->label ?? 'N/A' }}</p>
            </div>

            {{-- Birth Date --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Date de naissance</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">{{ \Carbon\Carbon::parse($student->date_of_birth)->age }} ans</p>
            </div>

            {{-- pays --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Pays de naissance</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $student->pays ?? 'N/A' }}</p>
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
                <p class="text-2xl font-bold text-gray-900">{{ $student->gender === 'male' ? 'Masculin' : 'Féminin' }}</p>
            </div>

            {{-- School Year --}}
            <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide">Année scolaire</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $student->classe->schoolYear->year ?? 'N/A' }}</p>
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
                    <x-button href="{{ route('students.edit', $student) }}" variant="primary">
                        Modifier l'étudiant
                    </x-button>
                    <form method="POST" action="{{ route('students.destroy', $student) }}" class="inline-block"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger">
                            Supprimer
                        </x-button>
                    </form>
                </div>
            </div>
        </div>


        {{-- Contenu Principal des Détails --}}
        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">

            <div class="flex flex-col sm:flex-row items-center sm:items-start mb-8 border-b pb-6">

                {{-- Section Photo --}}
                <div class="mb-6 sm:mb-0 sm:mr-8 flex-shrink-0 relative">
                    <img
                        src="{{ $student->photo ? asset('storage/' . $student->photo) : 'https://cdn-icons-png.flaticon.com/512/5850/5850276.png' }}"
                        alt="Photo de {{ $student->name }}"
                        class="profile-pic w-36 h-36 rounded-full border-4 border-indigo-500 shadow-xl ring-4 ring-gray-100"
                    >
                </div>

                {{-- Informations Clés et Actions --}}
                <div class="flex-grow text-center sm:text-left">
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $student->name }}</h3>
                    <p class="text-lg text-gray-600">Identifiant Étudiant : **#{{ $student->id }}**</p>

                    <div class="mt-6 flex justify-center sm:justify-start space-x-4">
                        <a href="{{ route('students.edit', $student->id) }}" class="px-6 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-[1.01]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Modifier le Dossier</span>
                        </a>
                         {{-- 🥇 NOUVEAU BOUTON : GÉNÉRER CERTIFICAT --}}
                        <a href="{{ route('students.certificate', $student->id) }}"
                           target="_blank" {{-- Ouvre dans un nouvel onglet, idéal pour les PDF/Impressions --}}
                           class="px-6 py-2.5 bg-yellow-600 text-white font-bold rounded-xl hover:bg-yellow-700 transition duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-[1.01]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586zM9 13v6h6m-3-3l-3 3"></path></svg>
                            <span>Certificat de Scolarité</span>
                        </a>
                        <a href="{{ route('classes.students', $student->class_id) }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition duration-300 flex items-center space-x-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Retour à la Liste</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Grille des Détails (Style "Carte de Statistiques" du dashboard) --}}
            <h2 class="text-2xl font-bold text-gray-700 mt-6 mb-5 border-b pb-3">Détails Administratifs</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Date de Naissance --}}
                <div class="detail-card bg-blue-50/50 hover:bg-blue-100/70 border-blue-200">
                    <p class="text-sm font-semibold text-blue-700 uppercase tracking-widest">Date de Naissance</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</p>
                </div>

                {{-- Genre --}}
                <div class="detail-card bg-purple-50/50 hover:bg-purple-100/70 border-purple-200">
                    <p class="text-sm font-semibold text-purple-700 uppercase tracking-widest">Genre</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $student->gender == 'M' ? 'Masculin ♂️' : 'Féminin ♀️' }}</p>
                </div>

                {{-- Classe Actuelle (Répétition pour insister, style différent) --}}
                <div class="detail-card bg-indigo-50/50 hover:bg-indigo-100/70 border-indigo-200">
                    <p class="text-sm font-semibold text-indigo-700 uppercase tracking-widest">Classe Attribuée</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $student->classe->label ?? 'N/A' }}</p>
                </div>

                {{-- Date d'Enregistrement --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Date d'Inscription</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $student->created_at->format('d/m/Y') }}</p>
                </div>

                 {{-- Heure d'Enregistrement --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Heure d'Inscription</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $student->created_at->format('H:i:s') }}</p>
                </div>

                {{-- ID de l'Étudiant (Répétition pour l'affichage séparé) --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Référence Unique</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">ID: **{{ $student->matricule }}**</p>
                </div>

            </div>

        </div>

    </main>

    <style>
        .detail-card {
            /* Style inspiré des cartes de statistiques de votre dashboard */
            @apply p-6 rounded-2xl shadow-sm border transition-all duration-300 transform hover:scale-[1.01];
        }
    </style>

    {{-- FOOTER (Copie exacte de votre dashboard) --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <p class="text-sm text-gray-500 font-medium">© {{ date('Y') }} Lycée de Balbala</p>
                    <div class="flex space-x-1 items-center">
                        <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                        <div class="w-2 h-2 bg-white border border-gray-300 rounded-full"></div>
                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    </div>
                </div>
                <div class="flex items-center space-x-8 text-sm text-gray-500 font-light">
                    <span>Version <strong class="text-gray-700">2.1.0</strong></span>
                    <span class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.74 21 3 13.26 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.12.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <span>Support: +253 77 <strong class="text-gray-700">12 34 56</strong></span>
                    </span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>

    </x-card>
@endsection

