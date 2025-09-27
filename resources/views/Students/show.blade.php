<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Détails de l'Étudiant : {{ $student->name ?? 'Chargement...' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .profile-pic {
            object-fit: cover;
            transition: transform 0.3s ease-in-out;
        }
        .profile-pic:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Simulation des données si la variable $student n'est pas définie (À RETIRER en production) --}}


    {{-- HEADER (Copie exacte de votre dashboard) --}}
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
                        <p class="text-xs text-gray-500 font-medium">Gestion Scolaire</p>
                    </div>
                </div>

                {{-- Profil et Déconnexion (Adapté pour la démo) --}}
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 group cursor-pointer p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->email ?? 'Administrateur' }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                            <span class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
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

    {{-- MAIN CONTENT (Utilise la même structure max-w-7xl) --}}
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Bande de Titre (Adaptée de la section Bienvenue) --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-6 sm:p-8 mb-8 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-indigo-200 uppercase tracking-widest mb-1">Dossier Élève</p>
                    <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
                        Fiche Détaillée de **{{ $student->name }}**
                    </h2>
                    <p class="text-indigo-100 text-lg mt-2">
                        Classe Actuelle :  {{ $student->classe->label ?? 'N/A' }}
                    </p>
                </div>
                <div class="hidden md:block opacity-80">
                    {{-- Icône Étudiant --}}
                    <svg class="w-12 h-12 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Contenu Principal des Détails --}}
        <div class="bg-white p-8 sm:p-10 rounded-2xl shadow-xl border border-gray-100">

            <div class="flex flex-col sm:flex-row items-center sm:items-start mb-8 border-b pb-6">

                {{-- Section Photo --}}
                <div class="mb-6 sm:mb-0 sm:mr-8 flex-shrink-0 relative">
                    <img
                        src="{{ $student->photo ? asset('storage/' . $student->photo) : 'https://cdn-icons-png.flaticon.com/512/5850/5850276.png' }}"
                        alt="Photo de {{ $student->name }}"
                        class="profile-pic w-36 h-36 rounded-full border-4 border-indigo-500 shadow-xl ring-4 ring-gray-100"
                    >
                </div>

                {{-- Informations Clés et Actions --}}
                <div class="flex-grow text-center sm:text-left">
                    <h3 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $student->name }}</h3>
                    <p class="text-lg text-gray-600">Identifiant Étudiant : **#{{ $student->id }}**</p>

                    <div class="mt-6 flex justify-center sm:justify-start space-x-4">
                        <a href="{{ route('students.edit', $student->id) }}" class="px-6 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition duration-300 flex items-center space-x-2 shadow-lg hover:shadow-xl transform hover:scale-[1.01]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            <span>Modifier le Dossier</span>
                        </a>
                        <a href="{{ route('classes.students', $student->classe_id) }}" class="px-6 py-2.5 bg-gray-200 text-gray-800 font-bold rounded-xl hover:bg-gray-300 transition duration-300 flex items-center space-x-2 shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            <span>Retour à la Liste</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Grille des Détails (Style "Carte de Statistiques" du dashboard) --}}
            <h2 class="text-2xl font-bold text-gray-700 mt-6 mb-5 border-b pb-3">Détails Administratifs</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Date de Naissance --}}
                <div class="detail-card bg-blue-50/50 hover:bg-blue-100/70 border-blue-200">
                    <p class="text-sm font-semibold text-blue-700 uppercase tracking-widest">Date de Naissance</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</p>
                </div>

                {{-- Genre --}}
                <div class="detail-card bg-purple-50/50 hover:bg-purple-100/70 border-purple-200">
                    <p class="text-sm font-semibold text-purple-700 uppercase tracking-widest">Genre</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $student->gender == 'M' ? 'Masculin ♂️' : 'Féminin ♀️' }}</p>
                </div>

                {{-- Classe Actuelle (Répétition pour insister, style différent) --}}
                <div class="detail-card bg-indigo-50/50 hover:bg-indigo-100/70 border-indigo-200">
                    <p class="text-sm font-semibold text-indigo-700 uppercase tracking-widest">Classe Attribuée</p>
                    <p class="text-3xl font-extrabold text-gray-900 mt-2">{{ $student->classe->label ?? 'N/A' }}</p>
                </div>

                {{-- Date d'Enregistrement --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Date d'Inscription</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $student->created_at->format('d/m/Y') }}</p>
                </div>

                 {{-- Heure d'Enregistrement --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Heure d'Inscription</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">{{ $student->created_at->format('H:i:s') }}</p>
                </div>

                {{-- ID de l'Étudiant (Répétition pour l'affichage séparé) --}}
                <div class="detail-card bg-gray-50 hover:bg-gray-100/70 border-gray-200">
                    <p class="text-sm font-medium text-gray-500">Référence Unique</p>
                    <p class="text-lg font-bold text-gray-900 mt-1">ID: **{{ $student->matricule }}**</p>
                </div>

            </div>

        </div>

    </main>

    <style>
        .detail-card {
            /* Style inspiré des cartes de statistiques de votre dashboard */
            @apply p-6 rounded-2xl shadow-sm border transition-all duration-300 transform hover:scale-[1.01];
        }
    </style>

    {{-- FOOTER (Copie exacte de votre dashboard) --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <p class="text-sm text-gray-500 font-medium">© {{ date('Y') }} Lycée de Balbala</p>
                    <div class="flex space-x-1 items-center">
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
