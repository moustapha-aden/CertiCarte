<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Classe - {{ $classe->label }} - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen">

    {{-- Header avec profil et déconnexion intégrés --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo et Titre --}}
                <div class="flex items-center space-x-4">
                    <div
                        class="w-10 h-10 bg-gradient-to-br from-blue-700 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z" />
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala</h1>
                        <p class="text-xs text-gray-500 font-medium">Détails de la Classe</p>
                    </div>
                </div>

                {{-- Navigation et Actions --}}
                <div class="flex items-center space-x-6">
                    {{-- Liens de navigation --}}
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Dashboard
                        </a>
                        <a href="{{ route('classes.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Classes
                        </a>
                        <a href="{{ route('students.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Étudiants
                        </a>
                    </div>

                    {{-- Profil et Déconnexion Intégrés --}}
                    <div class="relative group">
                        <div
                            class="flex items-center space-x-3 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::user()->email ?? 'Administrateur' }}
                                </p>
                            </div>
                            <div
                                class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                                <span
                                    class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        {{-- Menu déroulant avec le bouton de déconnexion --}}
                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    <p class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'Administrateur' }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-800 transition-colors flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z" />
                                        </svg>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="max-w-7xl mx-auto p-8 pt-10 sm:px-6 lg:px-8">

        {{-- Breadcrumbs --}}
        <nav class="mb-6" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-indigo-600 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                    </a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <a href="{{ route('classes.index') }}"
                        class="text-gray-500 hover:text-indigo-600 transition-colors">Classes</a>
                </li>
                <li>
                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                </li>
                <li>
                    <span class="text-gray-900 font-medium">{{ $classe->label }}</span>
                </li>
            </ol>
        </nav>

        {{-- En-tête avec informations principales --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
                {{-- Icône de classe --}}
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                </div>

                {{-- Informations principales --}}
                <div class="flex-grow text-center sm:text-left">
                    <h1 class="text-4xl font-extrabold mb-2">{{ $classe->label }}</h1>
                    <p class="text-xl text-indigo-100 mb-4">Année scolaire : {{ $classe->schoolYear->year ?? 'N/A' }}
                    </p>
                    <p class="text-lg text-indigo-200">
                        {{ $students->total() }} étudiant{{ $students->total() > 1 ? 's' : '' }}
                        inscrit{{ $students->total() > 1 ? 's' : '' }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                    <a href="{{ route('classes.edit', $classe) }}"
                        class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-100 transition duration-200 shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        <span>Modifier</span>
                    </a>
                    <a href="{{ route('classes.index') }}"
                        class="px-6 py-3 bg-indigo-500 text-white font-semibold rounded-lg hover:bg-indigo-400 transition duration-200 shadow-md flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        <span>Retour</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Détails de la classe --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100 mb-8">

            {{-- Titre de la section --}}
            <div class="mb-8 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    <span>Informations Détaillées</span>
                </h2>
            </div>

            {{-- Grille des informations --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- Nom de la classe --}}
                <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Nom de la classe</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $classe->label }}</p>
                </div>

                {{-- Année scolaire --}}
                <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Année scolaire</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $classe->schoolYear->year ?? 'N/A' }}</p>
                </div>

                {{-- Nombre d'étudiants --}}
                <div class="p-6 bg-indigo-50 rounded-xl border border-indigo-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Étudiants</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $students->total() }}</p>
                    <p class="text-sm text-gray-600 mt-1">inscrit{{ $students->total() > 1 ? 's' : '' }}</p>
                </div>

                {{-- Date de création --}}
                <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Créée le</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $classe->created_at->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600 mt-1">À {{ $classe->created_at->format('H:i') }}</p>
                </div>

                {{-- ID de la classe --}}
                <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">ID Classe</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">#{{ $classe->id }}</p>
                    <p class="text-sm text-gray-600 mt-1">Référence unique</p>
                </div>

                {{-- Dernière modification --}}
                <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide">Modifiée le</h3>
                    </div>
                    <p class="text-2xl font-bold text-gray-900">{{ $classe->updated_at->format('d/m/Y') }}</p>
                    <p class="text-sm text-gray-600 mt-1">À {{ $classe->updated_at->format('H:i') }}</p>
                </div>
            </div>

            {{-- Actions supplémentaires --}}
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                    <div class="text-sm text-gray-600">
                        <p>Dernière mise à jour : {{ $classe->updated_at->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('classes.edit', $classe) }}"
                            class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200">
                            Modifier la classe
                        </a>
                        <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Liste des étudiants --}}
        <div class="bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
            <div class="mb-6 border-b pb-4">
                <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    <span>Étudiants de cette classe</span>
                </h2>
            </div>

            @if ($students->count() > 0)
                {{-- Tableau des étudiants --}}
                <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-indigo-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Photo
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Nom & Prénom
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Matricule
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Date de naissance
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Genre
                                </th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($students as $student)
                                <tr class="hover:bg-indigo-50/30 transition-colors">
                                    {{-- Photo --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                                            class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                    </td>

                                    {{-- Nom --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                                    </td>

                                    {{-- Matricule --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700 font-medium">
                                            {{ $student->matricule ?? 'N/A' }}</div>
                                    </td>

                                    {{-- Date de naissance --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                                    </td>

                                    {{-- Genre --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->gender === 'male' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $student->gender === 'male' ? 'Masculin' : 'Féminin' }}
                                        </span>
                                    </td>

                                    {{-- Actions --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('students.show', $student) }}"
                                                class="text-indigo-600 hover:text-indigo-800 transition-colors p-1 rounded-md hover:bg-indigo-50"
                                                title="Voir les détails">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('students.edit', $student) }}"
                                                class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded-md hover:bg-blue-50"
                                                title="Modifier">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($students->hasPages())
                    <div class="mt-8">
                        {{ $students->links() }}
                    </div>
                @endif
            @else
                {{-- État vide --}}
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun étudiant dans cette classe</h3>
                    <p class="text-sm text-gray-600 mb-6">Cette classe ne contient aucun étudiant pour le moment.</p>
                    <a href="{{ route('students.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Ajouter un étudiant
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>

</html>
