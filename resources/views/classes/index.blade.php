<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- 🛑 NOUVEL EN-TÊTE (HEADER) --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Titre / Navigation --}}
                <div class="flex items-center space-x-4">
                    <div class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala 🎓</div>
                    <a href="{{ route('students.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150 border-l pl-4 hidden sm:block">
                        Gérer les Étudiants
                    </a>
                </div>

                {{-- Actions / Profil --}}
                <div class="flex items-center space-x-4">
                    {{-- Bouton Ajouter une Classe --}}
                    <a href="{{ route('classes.create') }}"
                       class="px-3 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 text-sm flex items-center space-x-1 shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        <span>Nouvelle Classe</span>
                    </a>

                    {{-- Lien vers le Dashboard (Simulé) --}}
                    <a href="/dashboard" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150 hidden md:block">
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>
    {{-- 🛑 FIN DE L'EN-TÊTE --}}

    <div class="max-w-7xl mx-auto p-8 pt-10 sm:px-6 lg:px-8">

        <div class="max-w-6xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            {{-- Titre de la Page (Simple, sans les boutons qui sont dans le Header) --}}
            <div class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Catalogue des Classes</span>
                </h1>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Grille des Classes --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($classes as $classe)
                    {{-- Lien cliquable vers la liste des étudiants de cette classe --}}
                    <a href="{{ route('classes.students', $classe->id) }}"
                       class="block p-6 bg-white border border-gray-200 rounded-xl shadow-md hover:shadow-xl hover:ring-2 hover:ring-indigo-500 transition-all duration-300 transform hover:scale-[1.02]">

                        <div class="flex items-center justify-between">
                            <span class="text-xl font-extrabold text-indigo-700">{{ $classe->label }}</span>
                            <svg class="w-6 h-6 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </div>

                        <p class="mt-4 text-sm font-medium text-gray-500">
                            Total Élèves :
                            {{ $classe->students()->count() }} {{-- Ceci pourrait être optimisé avec withCount() --}}
                        </p>
                        <p class="text-xs text-indigo-400 mt-1">
                            Cliquez pour voir la liste des étudiants
                        </p>
                    </a>
                @empty
                    <div class="col-span-full p-6 text-center text-gray-500 bg-gray-100 rounded-lg">
                        <p class="font-medium mb-2">Aucune classe n'a été enregistrée pour le moment.</p>
                        <a href="{{ route('classes.create') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">
                            Cliquez ici pour en ajouter une.
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Liens de Pagination --}}
            <div class="mt-8">
                {{-- Assurez-vous que votre contrôleur passe les classes paginées si vous utilisez ceci --}}
                {{-- $classes->links() --}}
            </div>

        </div>
    </div>
</body>
</html>
