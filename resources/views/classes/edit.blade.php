<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Classe {{ $classe->label }} - Lycée de Balbala</title>
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
                        <p class="text-xs text-gray-500 font-medium">Modification de Classe</p>
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
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-100">

            {{-- Breadcrumbs --}}
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    <li>
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-500 hover:text-indigo-600 transition-colors">
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
                            class="text-gray-500 hover:text-indigo-600 transition-colors">
                            Classes
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
                        <span class="text-gray-900 font-medium">Modifier la Classe "{{ $classe->label }}"</span>
                    </li>
                </ol>
            </nav>

            {{-- Titre --}}
            <div class="mb-8 border-b pb-4">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    <span>Modifier la Classe "{{ $classe->label }}"</span>
                </h1>
                <p class="text-gray-600 mt-2">Modifiez les informations de cette classe ci-dessous.</p>
            </div>

            {{-- Informations sur la classe --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Informations actuelles</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-medium">Classe:</span> {{ $classe->label }} •
                            <span class="font-medium">Année:</span> {{ $classe->schoolYear->year ?? 'Non définie' }} •
                            <span class="font-medium">Étudiants:</span> {{ $classe->students->count() }}
                        </p>
                    </div>
                    <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                    </svg>
                </div>
            </div>

            {{-- Formulaire --}}
            <form method="POST" action="{{ route('classes.update', $classe) }}" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nom de la classe --}}
                <div class="space-y-2">
                    <label for="label" class="block text-sm font-medium text-gray-700">
                        Nom de la classe <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="label" id="label" value="{{ old('label', $classe->label) }}"
                        required autofocus placeholder="Ex: 2nde A, Terminale S, 1ère L..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                    @error('label')
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
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
                    <select name="year_id" id="year_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        <option value="">Sélectionnez une année scolaire</option>
                        @foreach ($schoolYears as $year)
                            <option value="{{ $year->id }}"
                                {{ old('year_id', $classe->year_id) == $year->id ? 'selected' : '' }}>
                                {{ $year->year }}
                            </option>
                        @endforeach
                    </select>
                    @error('year_id')
                        <p class="text-sm text-red-600 flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z" />
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Boutons d'action --}}
                <div class="flex items-center justify-between pt-6 border-t">
                    {{-- Bouton de suppression --}}
                    <button type="button" onclick="deleteClass()"
                        class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition duration-200 flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <span>Supprimer</span>
                    </button>

                    {{-- Boutons de navigation --}}
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('classes.index') }}"
                            class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                            Annuler
                        </a>
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>Mettre à jour</span>
                        </button>
                    </div>
                </div>
            </form>

            {{-- Formulaire de suppression caché --}}
            <form id="deleteForm" method="POST" action="{{ route('classes.destroy', $classe) }}"
                style="display: none;">
                @csrf
                @method('DELETE')
            </form>

            {{-- Avertissement pour la suppression --}}
            @if ($classe->students->count() > 0)
                <div class="mt-6 p-4 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-800">Attention</h3>
                            <p class="text-sm text-yellow-700 mt-1">
                                Cette classe contient {{ $classe->students->count() }} étudiant(s).
                                Vous devez d'abord réassigner ou supprimer ces étudiants avant de pouvoir supprimer la
                                classe.
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function deleteClass() {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette classe ? Cette action est irréversible.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>

</body>

</html>
