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
                    {{-- Lien cliquable vers la liste des étudiants de cette classe --}}
                    <a href="{{ route('classes.students', $classe->id) }}"
                       class="block p-6 bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl hover:ring-2 hover:ring-indigo-500 transition-all duration-300 transform hover:scale-[1.02]">

                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xl font-extrabold text-indigo-700">{{ $classe->label }}</span>
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>

                        <div class="space-y-2">
                            <p class="text-sm font-medium text-gray-500">
                                Total Élèves: <span class="font-bold text-gray-900">{{ $classe->students->count() }}</span>
                            </p>
                            @if($classe->schoolYear)
                                <p class="text-xs text-indigo-400">
                                    Année: {{ $classe->schoolYear->year }}
                                </p>
                            @endif
                            <p class="text-xs text-indigo-400 mt-1">
                                Cliquez pour voir la liste des étudiants
                            </p>
                        </div>
                    </a>
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
