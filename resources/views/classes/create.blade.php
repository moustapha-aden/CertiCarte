<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une Nouvelle Classe - Lycée de Balbala</title>
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
                        <p class="text-xs text-gray-500 font-medium">Création de Classe</p>
                    </div>
                </div>

                {{-- Navigation et Actions --}}
                <div class="flex items-center space-x-6">
                    {{-- Liens de navigation --}}
                    <div class="hidden md:flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Dashboard
                        </a>
                        <a href="{{ route('classes.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Classes
                        </a>
                        <a href="{{ route('students.index') }}" class="text-sm font-medium text-gray-500 hover:text-indigo-600 transition duration-150">
                            Étudiants
                        </a>
                    </div>

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
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            {{-- Breadcrumb --}}
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-indigo-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <a href="{{ route('classes.index') }}" class="ml-1 text-gray-700 hover:text-indigo-600 md:ml-2">Classes</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-500 md:ml-2">Créer</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Titre --}}
            <div class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Créer une Nouvelle Classe</span>
                </h1>
                <p class="text-gray-600 mt-2">Remplissez les informations ci-dessous pour créer une nouvelle classe.</p>
            </div>

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('classes.store') }}" class="space-y-6">
                @csrf

                {{-- Nom de la classe --}}
                <div class="space-y-2">
                    <label for="label" class="block text-sm font-medium text-gray-700">
                        Nom de la classe <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="label" 
                        id="label" 
                        value="{{ old('label') }}"
                        required
                        autofocus
                        placeholder="Ex: 2nde A, Terminale S, 1ère L..."
                         class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                    @error('label')
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Année scolaire --}}
                <div class="space-y-2">
                    <label for="year_id" class="block text-sm font-medium text-gray-700">
                        Année scolaire <span class="text-red-500">*</span>
                    </label>
                    <select 
                        name="year_id" 
                        id="year_id" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    >
                        <option value="">Sélectionnez une année scolaire</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('year_id')
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Boutons d'action --}}
                <div class="flex items-center justify-end space-x-4 pt-6 border-t">
                    <a href="{{ route('classes.index') }}" 
                       class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span>Créer la Classe</span>
                    </button>
                </div>
            </form>

            {{-- Informations supplémentaires --}}
            <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-800">Conseils pour le nom de classe</h3>
                        <ul class="text-sm text-blue-700 mt-1 space-y-1">
                            <li>• Utilisez des formats clairs comme "2nde A", "Terminale S", "1ère L"</li>
                            <li>• Évitez les caractères spéciaux et les espaces multiples</li>
                            <li>• Chaque classe doit avoir un nom unique</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
