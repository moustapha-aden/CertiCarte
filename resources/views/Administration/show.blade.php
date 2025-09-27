<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'utilisateur - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z"/>
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala</h1>
                        <p class="text-xs text-gray-500 font-medium">Détails utilisateur</p>
                    </div>
                </div>

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
    <main class="max-w-3xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-3xl p-8 shadow-2xl border border-gray-100">

            <h1 class="text-3xl font-extrabold text-gray-900 text-center mb-6">
                Détails de l'utilisateur
            </h1>

            <ul class="bg-gray-50 p-6 rounded-xl shadow-inner space-y-3 text-gray-700">
                <li><strong>ID :</strong> {{ $user->id }}</li>
                <li><strong>Nom :</strong> {{ $user->name }}</li>
                <li><strong>Email :</strong> {{ $user->email }}</li>
                <li><strong>Rôle :</strong> {{ ucfirst($user->role) }}</li>
                <li><strong>Créé le :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</li>
                <li><strong>Mis à jour le :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</li>
            </ul>

            <div class="text-center mt-6 space-x-3">
                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 font-semibold">Éditer</a>
                <a href="{{ route('users.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 font-semibold">Retour à la liste</a>
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
