<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @isset($currentClasse)
            Élèves de la Classe : {{ $currentClasse->label }}
        @else
            Liste de Tous les Étudiants
        @endisset
    </title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">

    <div class="max-w-6xl mx-auto bg-white p-6 rounded-xl shadow-lg border border-gray-100">

        {{-- HEADER DYNAMIQUE --}}
        <div class="flex justify-between items-center mb-6 border-b pb-4">

            {{-- TITRE --}}
            @isset($currentClasse)
                {{-- Titre si nous filtrons par classe --}}
                <h1 class="text-3xl font-bold text-gray-800 flex items-center space-x-2">
                    <span class="text-indigo-600">🎓</span>
                    <span>Élèves de : **{{ $currentClasse->label }}**</span>
                </h1>
            @else
                {{-- Titre par défaut si nous affichons tous les élèves --}}
                <h1 class="text-3xl font-bold text-gray-800">📋 Liste de Tous les Étudiants</h1>
            @endisset

            {{-- BOUTONS D'ACTION --}}
            <div class="flex space-x-3">
                @isset($currentClasse)
                    {{-- Bouton pour retourner à la liste des classes --}}
                    <a href="{{ route('classes.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition duration-300 shadow-md flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span>Retour Classes</span>
                    </a>
                @endisset

                {{-- Bouton pour ajouter un étudiant --}}
                <a href="{{ route('students.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Ajouter un Élève</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        {{-- TABLEAU DES ÉTUDIANTS --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nom & Prénom</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date Naissance</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">

                    {{-- DEBUT DE LA BOUCLE SUR LES ÉTUDIANTS --}}
                    @forelse ($students as $student)
                        <tr class="hover:bg-blue-50/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-medium">{{ $student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-indigo-600 font-medium">
                                {{-- Utilisation de 'label' comme convenu --}}
                                {{ $student->classe->label ?? 'Non assignée' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('students.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3 transition-colors">Voir</a>
                                <a href="{{ route('students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900 mr-3 transition-colors">Modifier</a>

                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-sm text-gray-500">
                                <p class="font-medium">⚠️ Aucun étudiant trouvé pour le moment.</p>
                                <a href="{{ route('students.create') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800">Cliquez ici pour en ajouter un.</a>
                            </td>
                        </tr>
                    @endforelse
                    {{-- FIN DE LA BOUCLE SUR LES ÉTUDIANTS --}}
                </tbody>
            </table>
        </div>

        {{-- Liens de Pagination --}}
        <div class="mt-6">
            {{ $students->links() }}
        </div>

        <div class="mt-6 text-center text-sm text-gray-500 border-t pt-4">
            {{-- Affichage du rôle basé sur votre information de contexte --}}
            @if(Auth::check() && Auth::user()->role === 'admin')
                <span class="text-green-600 font-semibold">Connecté en tant qu'Administrateur</span> |
            @endif
            <a href="/dashboard" class="text-indigo-600 hover:text-indigo-800">← Retour au Dashboard</a>
        </div>
    </div>

</body>
</html>
