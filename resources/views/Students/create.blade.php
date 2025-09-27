<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Enregistrer un Nouvel Étudiant - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Maintient la transition subtile pour le corps */
        body {
            transition: background-color 0.3s ease;
        }

        /* Style spécifique pour masquer la fonction 'file' par défaut et utiliser un style plus moderne */
        input[type="file"]::file-selector-button {
            background-color: #eef2ff; /* indigo-50 */
            color: #4f46e5; /* indigo-700 */
            border-right: 1px solid #c7d2fe; /* indigo-200 */
            padding-right: 1rem;
        }

        /* Ajout de style plus propre pour les dates et selects (pour cohérence avec le padding des inputs) */
        .custom-select-input {
             padding-top: 0.75rem; /* p-3 */
             padding-bottom: 0.75rem; /* p-3 */
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ************************************************************* --}}
    {{-- HEADER (Identique au Dashboard) --}}
    {{-- ************************************************************* --}}
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
                        <p class="text-xs text-gray-500 font-medium">Inscription des Élèves</p>
                    </div>
                </div>

                {{-- Profil et Déconnexion --}}
                <div class="flex items-center space-x-6">
                    @php
                        // S'assurer que Auth est disponible ou utiliser des valeurs par défaut
                        $userName = Auth::check() ? Auth::user()->name : 'M./Mme Proviseur';
                        $userRole = Auth::check() ? (Auth::user()->role ?? 'Administrateur') : 'Administrateur';
                        $userInitial = strtoupper(substr($userName, 0, 1));
                    @endphp
                    <div class="flex items-center space-x-3 group cursor-pointer p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ $userName }}</p>
                            <p class="text-xs text-gray-500">
                                {{ $userRole }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                            <span class="text-white text-md font-bold">{{ $userInitial }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') ?? '#' }}">
                        @csrf
                        <button type="submit"
                            class="hidden sm:flex bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 items-center space-x-2 border border-red-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z"/>
                            </svg>
                            <span class="hidden md:inline">Déconnexion</span>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </header>

    {{-- ************************************************************* --}}
    {{-- CONTENU PRINCIPAL (FORMULAIRE - Style Dashboard Appliqué) --}}
    {{-- ************************************************************* --}}
    <main class="flex-grow max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 w-full">

        {{-- Conteneur Principal (Comme une carte de section du dashboard) --}}
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">

            {{-- Titre de la section --}}
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h1 class="text-3xl font-extrabold text-gray-900 flex items-center space-x-3">
                    {{-- Icône cohérente avec l'action rapide 'Nouvel Élève' --}}
                    <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                    </svg>
                    <span>Nouvelle Inscription</span>
                </h1>
                {{-- Lien Retour (Meilleure cohérence : classes/étudiants) --}}
                <a href="{{ route('dashboard') ?? '#' }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition duration-150 flex items-center space-x-1 p-2 rounded-lg hover:bg-indigo-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Retour au Tableau de Bord</span>
                </a>
            </div>

            {{-- Formulaire de Création d'Étudiant --}}
            <form action="{{ route('students.store') ?? '#' }}" method="POST" class="space-y-8" enctype="multipart/form-data">
                @csrf

                {{-- Groupe 1: Informations de Base --}}
                <div class="p-6 border border-indigo-100 rounded-xl bg-indigo-50/30">
                    <h2 class="text-xl font-bold text-indigo-700 mb-4 pb-2 border-b border-indigo-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Informations Personnelles
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Champ Nom Complet --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Nom Complet <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="custom-select-input mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg shadow-sm px-3 bg-white focus:border-blue-500 focus:ring-blue-500 focus:outline-none transition duration-150">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champ Numéro d'Identification --}}
                        <div>
                            <label for="matricule" class="block text-sm font-medium text-gray-700">Numéro d'Identification <span class="text-red-500">*</span></label>
                            <input type="text" name="matricule" id="matricule" value="{{ old('matricule') }}" required maxlength="10"
                                class="custom-select-input mt-1 block w-full border border-gray-300 text-gray-900 rounded-lg shadow-sm px-3 bg-white focus:border-blue-500 focus:ring-blue-500 focus:outline-none transition duration-150">
                            @error('matricule')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champ Date de Naissance --}}
                        <div>
                            <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date de Naissance <span class="text-red-500">*</span></label>
                            <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required
                                class="custom-select-input mt-1 block w-full border border-gray-300 text-gray-900 rounded-lg shadow-sm px-3 bg-white focus:border-blue-500 focus:ring-blue-500 focus:outline-none transition duration-150">
                            @error('date_of_birth')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champ Genre --}}
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                            <select name="gender" id="gender" required
                                    class="custom-select-input mt-1 block w-full border border-gray-300 text-gray-900 rounded-lg shadow-sm px-3 bg-white focus:border-blue-500 focus:ring-blue-500 focus:outline-none transition duration-150">
                                <option value="">Sélectionnez le genre</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin (M)</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin (F)</option>
                            </select>
                            @error('gender')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Groupe 2: Inscription et Document --}}
                <div class="p-6 border border-purple-100 rounded-xl bg-purple-50/30">
                    <h2 class="text-xl font-bold text-purple-700 mb-4 pb-2 border-b border-purple-200 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/></svg>
                        Détails de Scolarité
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Champ Classe --}}
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Classe d'Affectation <span class="text-red-500">*</span></label>
                            <select name="class_id" id="class_id" required
                                    class="custom-select-input mt-1 block w-full border border-gray-300 text-gray-900 rounded-lg shadow-sm px-3 bg-white focus:border-purple-500 focus:ring-purple-500 focus:outline-none transition duration-150">
                                <option value="">Sélectionnez une classe</option>
                                @if(isset($classes) && is_iterable($classes))
                                    @foreach ($classes as $classe)
                                        <option value="{{ $classe->id }}" {{ old('class_id') == ($classe->id ?? null) ? 'selected' : '' }}>
                                            {{ $classe->label }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>⚠️ Erreur : Aucune classe disponible</option>
                                @endif
                            </select>
                            @error('class_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Champ Photo (Amélioration du style de l'input type="file") --}}
                        <div>
                            <label for="photo" class="block text-sm font-medium text-gray-700">Photo de l'Élève (Optionnel)</label>
                            <input type="file" name="photo" id="photo" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-purple-500 file:mr-4 file:py-3 file:px-4 file:rounded-r-none file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 transition duration-150">
                            <p class="text-xs text-gray-500 mt-1">Formats acceptés : JPG, PNG. Max. 2 Mo.</p>
                            @error('photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Bouton de Soumission (Bouton d'action Vert comme dans le dashboard) --}}
                <div class="pt-6">
                    <button type="submit" class="w-full px-4 py-3 bg-green-600 text-white font-extrabold text-lg rounded-xl hover:bg-green-700 transition duration-300 shadow-xl flex items-center justify-center space-x-2 transform hover:scale-[1.01] hover:shadow-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9 5h18a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>ENREGISTRER L'ÉLÈVE MAINTENANT</span>
                    </button>
                </div>
            </form>
        </div>

    </main>

    {{-- ************************************************************* --}}
    {{-- FOOTER (Identique au Dashboard) --}}
    {{-- ************************************************************* --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <p class="text-sm text-gray-500 font-medium">© {{ date('Y') }} Lycée de Balbala</p>
                    <div class="flex space-x-1 items-center">
                        {{-- Couleurs de Djibouti 🇩🇯 --}}
                        <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                        <div class="w-2 h-2 bg-white border border-gray-300 rounded-full"></div>
                        <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                    </div>
                </div>
                <div class="flex items-center space-x-8 text-sm text-gray-500 font-light">
                    <span>Version <strong class="text-gray-700">2.1.0</strong></span>
                    <span class="flex items-center space-x-1">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1C10.74 21 3 13.26 3 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.12.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                        <span>Support: +253 77 <strong class="text-gray-700">12 34 56</strong></span>
                    </span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
