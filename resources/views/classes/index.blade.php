<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header avec profil et déconnexion intégrés --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo et Titre --}}
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z"/>
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala</h1>
                        <p class="text-xs text-gray-500 font-medium">Gestion des Classes</p>
                    </div>
                </div>

                {{-- Navigation et Actions --}}
                <div class="flex items-center space-x-6">
                    {{-- Liens de navigation --}}
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Dashboard
                        </a>
                        <a href="{{ route('students.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Étudiants
                        </a>
                    </div>

                    {{-- Bouton Ajouter une Classe --}}
                    <a href="{{ route('classes.create') }}"
                       class="px-3 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 text-sm flex items-center space-x-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nouvelle Classe</span>
                    </a>

                    {{-- Profil et Déconnexion Intégrés --}}
                    <div class="relative group">
                        <div class="flex items-center space-x-3 cursor-pointer p-2 rounded-full hover:bg-gray-100 transition-colors">
                            <div class="text-right hidden sm:block">
                                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::user()->email ?? 'Administrateur' }}
                                </p>
                            </div>
                            <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                                <span class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                            </div>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        
                        {{-- Menu déroulant avec le bouton de déconnexion --}}
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                    <p class="font-semibold">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'Administrateur' }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-800 transition-colors flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z"/>
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

        <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            {{-- Titre et Filtre --}}
            <div class="mb-8 border-b pb-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-4 sm:space-y-0">
                    <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <span>Catalogue des Classes</span>
                    </h1>

                    {{-- Filtre par année scolaire --}}
                    <form method="GET" action="{{ route('classes.index') }}" class="flex items-center space-x-2">
                        <label for="year_id" class="text-sm font-medium text-gray-700">Année scolaire:</label>
                        <select name="year_id" id="year_id" onchange="this.form.submit()" 
                                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Toutes les années</option>
                            @foreach($schoolYears as $year)
                                <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                                    {{ $year->year }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Statistiques rapides --}}
            @if($selectedYearId)
                @php
                    $selectedYear = $schoolYears->firstWhere('id', $selectedYearId);
                    $totalStudents = $classes->sum(function($classe) { return $classe->students->count(); });
                @endphp
                <div class="mb-6 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-800">{{ $selectedYear->year ?? 'Année sélectionnée' }}</h3>
                            <p class="text-sm text-indigo-600">{{ $classes->count() }} classe(s) • {{ $totalStudents }} élève(s)</p>
                        </div>
                        <svg class="w-8 h-8 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                        </svg>
                    </div>
                </div>
            @endif

            {{-- Grille des Classes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($classes as $classe)
                    {{-- Carte de classe avec actions --}}
                    <div class="p-6 bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:scale-[1.02]">
                        
                        {{-- En-tête avec titre et actions --}}
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xl font-extrabold text-indigo-700">{{ $classe->label }}</span>
                            <div class="flex items-center space-x-2">
                                {{-- Bouton Modifier --}}
                                <a href="{{ route('classes.edit', $classe) }}" 
                                   class="p-2 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                   title="Modifier la classe">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                
                                {{-- Bouton Supprimer --}}
                                <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la classe {{ $classe->label }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="Supprimer la classe">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                        {{-- Informations de la classe --}}
                        <div class="space-y-2 mb-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Élèves: <span class="font-bold text-gray-900">{{ $classe->students->count() }}</span>
                            </p>
                            @if($classe->schoolYear)
                                <p class="text-xs text-indigo-400">
                                    Année: {{ $classe->schoolYear->year }}
                                </p>
                            @endif
                        </div>

                        {{-- Bouton pour voir les étudiants --}}
                        <a href="{{ route('classes.students', $classe->id) }}"
                           class="block w-full text-center px-4 py-2 bg-indigo-50 text-indigo-700 font-medium rounded-lg hover:bg-indigo-100 transition-colors">
                            Voir les étudiants
                            <svg class="w-4 h-4 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full p-6 text-center text-gray-500 bg-gray-100 rounded-lg">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <p class="font-medium mb-2">Aucune classe trouvée pour cette année scolaire.</p>
                        <a href="{{ route('classes.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                            Cliquez ici pour en ajouter une.
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($classes->hasPages())
                <div class="mt-8">
                    {{ $classes->appends(request()->query())->links() }}
                </div>
            @endif

        </div>
    </div>
</body>
</html>
