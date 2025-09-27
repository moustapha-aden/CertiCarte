<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        @isset($currentClasse)
            Élèves de la Classe : {{ $currentClasse->label }} - Lycée de Balbala
        @else
            Liste de Tous les Étudiants - Lycée de Balbala
        @endisset
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ajout d'une transition subtile pour le corps */
        body {
            transition: background-color 0.3s ease;
        }
        /* Style pour les liens de pagination (si la pagination de Laravel est utilisée) */
        .pagination a, .pagination span {
            padding: 0.5rem 0.75rem;
            margin-left: 0.25rem;
            margin-right: 0.25rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
        }
        .pagination a {
            color: #4f46e5; /* indigo-600 */
            border: 1px solid #e0e7ff; /* indigo-100 */
            background-color: #f5f5f5; /* gray-100 */
        }
        .pagination span.active {
            background-color: #4f46e5; /* indigo-600 */
            color: white;
            border: 1px solid #4f46e5;
        }

        /* Style uniforme pour les inputs/selects de filtrage */
        .filter-input {
            @apply block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm h-10 px-4;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen p-4 sm:p-8 flex flex-col">

    <div class="max-w-7xl mx-auto w-full bg-white p-6 sm:p-8 rounded-3xl shadow-2xl border border-gray-100">

        {{-- ************************************************************* --}}
        {{-- HEADER DYNAMIQUE ET BOUTONS D'ACTION --}}
        {{-- ************************************************************* --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pb-4 border-b">

            {{-- TITRE --}}
            @isset($currentClasse)
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center space-x-3 mb-4 sm:mb-0">
                    <span class="w-8 h-8 text-indigo-600 flex items-center justify-center">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 11-6-11-6zm0 13l-11-6 11-6 11 6-11 6zm0 2.18L18.09 15 12 18.73 5.91 15 12 18.18z"/></svg>
                    </span>
                    <span class="tracking-tight">Classe : **{{ $currentClasse->label }}**</span>
                </h1>
            @else
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center space-x-3 mb-4 sm:mb-0">
                    <span class="w-8 h-8 text-blue-600 flex items-center justify-center">
                        <svg class="w-full h-full" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
                    </span>
                    <span class="tracking-tight">Liste Générale des Étudiants</span>
                </h1>
            @endisset

            {{-- BOUTONS D'ACTION --}}
            <div class="flex space-x-3">
                @isset($currentClasse)
                    <a href="{{ route('classes.index') ?? '#' }}" class="hidden md:flex px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-xl hover:bg-gray-200 transition duration-300 shadow-sm items-center space-x-2 border border-gray-200 text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Retour Classes</span>
                    </a>
                @endisset

                <a href="{{ route('students.create') ?? '#' }}" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-xl hover:bg-green-700 transition duration-300 shadow-lg flex items-center space-x-2 transform hover:scale-[1.005] text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Ajouter un Élève</span>
                </a>
            </div>
        </div>

        {{-- Message de Succès --}}
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        {{-- ************************************************************* --}}
        ## Contrôles de Filtrage et de Recherche
        {{-- ************************************************************* --}}
        <div class="mb-8 p-5 bg-gray-100 rounded-xl border border-gray-200 shadow-inner">
            {{-- La soumission se fera sur l'événement 'onchange' du sélecteur d'année --}}
            <form id="filter-form" method="GET" action="{{ route('students.index') ?? '#' }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

                {{-- 1. Filtre par Année Scolaire (Prioritaire) --}}
                <div>
                    <label for="year" class="block text-sm font-bold text-gray-700 mb-1">1. Année Scolaire <span class="text-red-500">*</span></label>
                    <select name="year" id="year" class="filter-input bg-white font-semibold"
                            onchange="document.getElementById('filter-form').submit()">
                        <option value="">-- Choisir une Année --</option>
                        {{-- Assurez-vous que $allYears est passé depuis votre contrôleur --}}
                        @if(isset($allYears) && is_iterable($allYears))
                            @foreach ($allYears as $year)
                                <option value="{{ $year }}" {{ (string)$year === request('year') ? 'selected' : '' }}>
                                    {{ $year }}
                                </option>
                            @endforeach
                        @else
                            {{-- Exemple statique si $allYears n'est pas passé --}}
                            <option value="2024" {{ '2024' === request('year') ? 'selected' : '' }}>2024-2025</option>
                            <option value="2023" {{ '2023' === request('year') ? 'selected' : '' }}>2023-2024</option>
                        @endif
                    </select>
                </div>

                {{-- 2. Filtre par Classe (Visible SEULEMENT après sélection de l'année) --}}
                <div @unless(request('year')) class="opacity-50 pointer-events-none" @endunless>
                    <label for="class_id" class="block text-sm font-bold text-gray-700 mb-1">2. Choisir une Classe</label>
                    <select name="class_id" id="class_id" class="filter-input bg-white">
                        <option value="">-- Toutes les Classes --</option>
                        {{-- Assurez-vous que $classesByYear est passé depuis votre contrôleur --}}
                        @if(request('year') && isset($classesByYear) && is_iterable($classesByYear))
                            @foreach ($classesByYear as $id => $label)
                                <option value="{{ $id }}" {{ (string)$id === request('class_id') ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        @else
                            {{-- Si aucune année n'est sélectionnée, ou aucune classe trouvée --}}
                            <option value="" disabled>Classes non disponibles</option>
                        @endif
                    </select>
                </div>

                {{-- 3. Champ de Recherche --}}
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Rechercher par Nom/ID</label>
                    <div class="relative">
                        <input type="search" name="search" id="search" placeholder="Nom ou ID..."
                               value="{{ request('search') }}"
                               class="filter-input pl-10"
                        >
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>

                {{-- Bouton d'Application / Réinitialisation --}}
                <div class="md:col-span-1">
                    @if(request()->hasAny(['search', 'class_id', 'year']))
                        {{-- Bouton de Réinitialisation --}}
                        <a href="{{ route('students.index') ?? '#' }}" class="w-full h-10 px-4 py-2 bg-red-500 text-white font-semibold rounded-xl hover:bg-red-600 transition duration-300 shadow-md flex items-center justify-center space-x-2 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            <span>Réinitialiser</span>
                        </a>
                    @else
                        {{-- Bouton d'Application (Par défaut) --}}
                        <button type="submit" class="w-full h-10 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition duration-300 shadow-md flex items-center justify-center space-x-2 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v1a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM4 12h16m-7 4h-2m-2-4v6m4-6v6"></path></svg>
                            <span>Appliquer Filtres</span>
                        </button>
                    @endif
                </div>
            </form>
        </div>

        ---

        {{-- TABLEAU DES ÉTUDIANTS (Le reste du code reste inchangé) --}}
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-lg">
            {{-- ... (Le tableau des étudiants) ... --}}
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-50/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">N° Ident.</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Nom & Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-indigo-700 uppercase tracking-wider">Né(e) le</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-indigo-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    @forelse ($students as $student)
                        <tr class="hover:bg-indigo-50/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $student->matricule ?? $student->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-semibold">
                                {{ $student->classe->label ?? 'Non assignée' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                <a href="{{ route('students.show', $student->id) ?? '#' }}" class="text-indigo-600 hover:text-indigo-800 transition-colors p-1 rounded-md hover:bg-indigo-50">Voir</a>
                                <a href="{{ route('students.edit', $student->id) ?? '#' }}" class="text-blue-600 hover:text-blue-800 transition-colors p-1 rounded-md hover:bg-blue-50">Modifier</a>

                                <form action="{{ route('students.destroy', $student->id) ?? '#' }}" method="POST" class="inline-block ml-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors p-1 rounded-md hover:bg-red-50" onclick="return confirm('Êtes-vous sûr de vouloir supprimer {{ $student->name }} ? Cette action est irréversible.')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 whitespace-nowrap text-center text-md text-gray-500 bg-gray-50">
                                <p class="font-bold mb-2">😭 Liste Vide : Aucun étudiant ne correspond aux filtres appliqués.</p>
                                <a href="{{ route('students.index') ?? '#' }}" class="mt-4 inline-block text-sm text-red-600 hover:text-red-800 font-medium">Réinitialiser les filtres</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Liens de Pagination --}}
        @if(isset($students) && method_exists($students, 'links'))
            <div class="mt-6 flex justify-end">
                {{ $students->links('vendor.pagination.tailwind') }}
            </div>
        @endif

        {{-- Footer de la Carte --}}
        <div class="mt-8 text-center text-sm text-gray-500 border-t pt-4">
            <p class="mb-2">
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <span class="text-green-600 font-semibold">Statut : Administrateur</span>
                @elseif(Auth::check())
                     <span class="text-yellow-600 font-semibold">Connecté</span>
                @endif
            </p>
            <a href="/dashboard" class="text-indigo-600 font-medium hover:text-indigo-800 transition duration-150 flex items-center justify-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                <span>Retourner au Tableau de Bord</span>
            </a>
        </div>
    </div>

</body>
</html>
