<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @isset($currentClasse)
            Élèves de la Classe : {{ $currentClasse->label }} - Lycée de Balbala
        @else
            Gestion des Étudiants - Lycée de Balbala
        @endisset
    </title>
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
                        <p class="text-xs text-gray-500 font-medium">
                            @isset($currentClasse)
                                Étudiants - {{ $currentClasse->label }}
                            @else
                                Gestion des Étudiants
                            @endisset
                        </p>
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

                    {{-- Bouton Ajouter un Étudiant --}}
                    <a href="{{ route('students.create') }}"
                        class="px-3 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 text-sm flex items-center space-x-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>Nouvel Étudiant</span>
                    </a>

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
                @isset($currentClasse)
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
                        <span class="text-gray-900 font-medium">{{ $currentClasse->label }}</span>
                    </li>
                @else
                    <li>
                        <span class="text-gray-900 font-medium">Étudiants</span>
                    </li>
                @endisset
            </ol>
        </nav>

        <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            {{-- Titre et Filtres --}}
            <div class="mb-8 border-b pb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                        <span>
                            @isset($currentClasse)
                                Étudiants de {{ $currentClasse->label }}
                            @else
                                Catalogue des Étudiants
                            @endisset
                        </span>
                    </h1>

                    {{-- Filtres --}}
                    <form method="GET" action="{{ route('students.index') }}" class="flex items-center space-x-2">
                        <label for="year" class="text-sm font-medium text-gray-700">Année:</label>
                        <select name="year" id="year" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Toutes les années</option>
                            @if (isset($allYears) && is_iterable($allYears))
                                @foreach ($allYears as $id => $year)
                                    <option value="{{ $id }}"
                                        {{ request('year') == $id ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        @if (request('year'))
                            <label for="class_id" class="text-sm font-medium text-gray-700">Classe:</label>
                            <select name="class_id" id="class_id" onchange="this.form.submit()"
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">Toutes les classes</option>
                                @if (isset($classesByYear) && is_iterable($classesByYear))
                                    @foreach ($classesByYear as $id => $label)
                                        <option value="{{ $id }}"
                                            {{ request('class_id') == $id ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        @endif

                        <div class="relative">
                            <input type="search" name="search" placeholder="Rechercher..."
                                value="{{ request('search') }}"
                                class="px-3 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>

                        @if (request()->hasAny(['search', 'class_id', 'year']))
                            <a href="{{ route('students.index') }}"
                                class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors text-sm">
                                Réinitialiser
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistiques rapides --}}
            @if (request('year') || request('class_id'))
                <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-800">
                                @if (request('class_id') && isset($classesByYear))
                                    {{ $classesByYear[request('class_id')] ?? 'Classe sélectionnée' }}
                                @elseif(request('year') && isset($allYears))
                                    {{ $allYears[request('year')] ?? 'Année sélectionnée' }}
                                @else
                                    Filtres appliqués
                                @endif
                            </h3>
                            <p class="text-sm text-indigo-600">{{ $students->count() }} étudiant(s) trouvé(s)</p>
                        </div>
                        <svg class="w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Tableau des Étudiants --}}
            <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-indigo-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Photo
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Nom & Prénom
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Matricule
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Classe
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Date de naissance
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Genre
                            </th>
                            <th
                                class="px-6 py-4 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse ($students as $student)
                            <tr class="hover:bg-indigo-50/30 transition-colors">
                                {{-- Photo --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                                </td>

                                {{-- Nom --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                                </td>

                                {{-- Matricule --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-700 font-medium">{{ $student->matricule ?? 'N/A' }}
                                    </div>
                                </td>

                                {{-- Classe --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $student->classe->label ?? 'Non assignée' }}
                                    </span>
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </a>
                                        <a href="{{ route('students.edit', $student) }}"
                                            class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded-md hover:bg-blue-50"
                                            title="Modifier">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <form method="POST" action="{{ route('students.destroy', $student) }}"
                                            class="inline-block"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer {{ $student->name }} ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition-colors p-1 rounded-md hover:bg-red-50"
                                                title="Supprimer">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 whitespace-nowrap text-center">
                                    <div class="max-w-md mx-auto">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
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
                                                Aucun étudiant ne correspond aux filtres appliqués.
                                            @else
                                                Commencez par ajouter votre premier étudiant.
                                            @endif
                                        </p>
                                        <a href="{{ route('students.create') }}"
                                            class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition-colors shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Ajouter un étudiant
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($students->hasPages())
                <div class="mt-8">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
</body>

</html>
