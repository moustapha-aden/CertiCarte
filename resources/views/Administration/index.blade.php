<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header identique au tableau de bord --}}
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
                        <p class="text-xs text-gray-500 font-medium">Liste des utilisateurs</p>
                    </div>
                </div>

                {{-- Profil et Déconnexion --}}
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 group cursor-pointer p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'M./Mme Proviseur' }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email ?? 'Administrateur' }}</p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                            <span class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="hidden sm:flex bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 items-center space-x-2 border border-red-200">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-extrabold text-gray-900">Liste des utilisateurs</h2>
                <a href="{{ route('users.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 transition-all">Créer un nouvel utilisateur</a>
            </div>

            {{-- Recherche --}}
            <form method="GET" action="{{ route('users.index') }}" class="flex mb-6 space-x-2">
                <input type="text" name="q" placeholder="Rechercher..." value="{{ request('q') }}" class="border rounded-xl px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-gray-500 text-white px-4 py-2 rounded-xl hover:bg-gray-600 transition-all">Rechercher</button>
            </form>

            {{-- Message de succès --}}
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tableau --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white rounded-xl shadow-md divide-y divide-gray-200">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase tracking-wider">Nom</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase tracking-wider">Rôle</th>
                            <th class="py-3 px-4 text-left text-sm font-semibold uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-blue-50 transition-colors">
                                <td class="py-2 px-4">{{ $user->id }}</td>
                                <td class="py-2 px-4">{{ $user->name }}</td>
                                <td class="py-2 px-4">{{ $user->email }}</td>
                                <td class="py-2 px-4">{{ ucfirst($user->role) }}</td>
                                <td class="py-2 px-4 space-x-2">
                                    <a href="{{ route('users.show', $user->id) }}" class="text-blue-500 hover:underline">Voir</a>
                                    <a href="{{ route('users.edit', $user->id) }}" class="text-yellow-500 hover:underline">Éditer</a>
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-center text-gray-500">Aucun utilisateur trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            © {{ date('Y') }} Lycée de Balbala - Gestion du Personnel
        </div>
    </footer>

</body>
</html>
