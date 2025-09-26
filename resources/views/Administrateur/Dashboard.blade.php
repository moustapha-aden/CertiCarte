<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tableau de Bord - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ajout d'une transition subtile pour le corps */
        body {
            transition: background-color 0.3s ease;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

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
                        <p class="text-xs text-gray-500 font-medium">Tableau de Bord Administrateur</p>
                    </div>
                </div>

                {{-- Profil et Déconnexion --}}
                <div class="flex items-center space-x-6">
                    <div class="flex items-center space-x-3 group cursor-pointer p-1 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name ?? 'M./Mme Proviseur' }}</p>
                            <p class="text-xs text-gray-500">
                                {{ Auth::user()->email ?? 'Administrateur' }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center ring-2 ring-gray-200 group-hover:ring-indigo-300 transition-all">
                            <span class="text-white text-md font-bold">{{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
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

    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">

        {{-- Bandeau de Bienvenue Amélioré --}}
        <div class="bg-gradient-to-br from-blue-700 to-indigo-600 rounded-3xl p-8 mb-10 text-white shadow-2xl transform transition-all duration-500 hover:scale-[1.01]">
            <div class="flex items-start justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold mb-2 leading-tight">Bonjour, {{ Auth::user()->name ?? 'M./Mme le Proviseur' }} !</h2>
                    <p class="text-indigo-100 text-lg">Un aperçu des statistiques clés de l'établissement.</p>
                    @php
                        // Nécessite l'extension intl dans PHP
                        // Logique de formatage de date conservée
                        $date = new DateTime();
                        $formatter = new IntlDateFormatter(
                            'fr_FR',
                            IntlDateFormatter::FULL,
                            IntlDateFormatter::NONE,
                            null,
                            null,
                            "EEEE d MMMM yyyy"
                        );
                        $today = $formatter->format($date);
                    @endphp
                    <p class="text-indigo-200 text-sm mt-4 font-medium flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span>{{ ucfirst($today) }}</span>
                        <span class="text-indigo-400">•</span>
                        <span>République de Djibouti 🇩🇯</span>
                    </p>
                </div>
                <div class="hidden md:block opacity-80">
                    <svg class="w-16 h-16 text-indigo-200" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.89-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                </div>
            </div>
        </div>

        {{-- Cartes de Statistiques --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">

            {{-- Carte 1: Élèves Inscrits (Corrigée de la duplication) --}}
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-500 uppercase tracking-widest">Élèves Inscrits</p>
                        {{-- Utilise la variable $eleves_inscrits --}}
                        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ number_format($eleves_inscrits ?? 1247, 0, ',', ' ') }}</p>
                        <div class="flex items-center mt-3">
                            {{-- Utilise la variable $evolution_eleves --}}
                            @if (($evolution_eleves ?? 5.2) >= 0)
                                <svg class="w-5 h-5 text-green-500 mr-1" fill="currentColor" viewBox="0 0 24 24" style="transform: rotate(180deg);">
                                    <path d="M7 14l5-5 5 5z"/>
                                </svg>
                                <span class="text-green-600 text-sm font-bold">+{{ number_format($evolution_eleves ?? 5.2, 1) }}%</span>
                            @else
                                <svg class="w-5 h-5 text-red-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M7 14l5-5 5 5z"/>
                                </svg>
                                <span class="text-red-600 text-sm font-bold">{{ number_format($evolution_eleves ?? -2.1, 1) }}%</span>
                            @endif
                            <span class="text-gray-500 text-xs ml-2">vs l'an dernier</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 4c0-1.11.89-2 2-2s2 .89 2 2-.89 2-2 2-2-.89-2-2zM4 18v-4h3v-2c0-1.1.9-2 2-2h2V8c0-1.1.9-2 2-2s2 .9 2 2v2h2c1.1 0 2 .9 2 2v2h3v4H4z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Carte 2: Classes Actives --}}
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-500 uppercase tracking-widest">Classes Actives</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ $classes_actives ?? 42 }}</p>
                        <div class="flex items-center mt-3">
                            <svg class="w-4 h-4 text-purple-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                            </svg>
                            <span class="text-gray-500 text-xs font-medium">De Seconde à Terminale</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z"/>
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Carte 3: Taux de Réussite --}}
            <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-600 uppercase tracking-widest">Taux de Réussite</p>
                        <p class="text-4xl font-extrabold text-gray-900 mt-2">{{ number_format($taux_reussite ?? 94.2, 1) }}%</p>
                        <div class="flex items-center mt-3">
                            @php
                                $status = ($taux_reussite ?? 94.2) > 90 ? ['Excellent', 'green'] : ( ($taux_reussite ?? 94.2) > 80 ? ['Bon', 'yellow'] : ['Faible', 'red'] );
                            @endphp
                            <svg class="w-5 h-5 text-{{ $status[1] }}-500 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-{{ $status[1] }}-600 text-sm font-bold">{{ $status[0] }}</span>
                        </div>
                    </div>
                    <div class="w-14 h-14 bg-yellow-50 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-8 border-gray-200">

        {{-- Section Actions Rapides --}}
        <div class="grid grid-cols-1 gap-8 mb-10">
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-3 flex items-center">
                    <svg class="w-6 h-6 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                    </svg>
                    Actions Rapides
                </h3>

                {{-- Utilisation de liens <a> au lieu de <button> si elles mènent à d'autres pages --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">

                    <a href="#" class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
                        <svg class="w-8 h-8 text-blue-600 mb-3 group-hover:text-blue-700 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                        </svg>
                        <h4 class="font-bold text-gray-900 mb-1">Rapports</h4>
                        <p class="text-xs text-gray-600">Générer et consulter</p>
                    </a>

                    <a href="{{ route('students.create') ?? '#' }}" class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
                       <svg class="w-8 h-8 text-green-600 mb-3 group-hover:text-green-700 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                        </svg>
                        <h4 class="font-bold text-gray-900 mb-1">Nouvel Élève</h4>
                        <p class="text-xs text-gray-600">Inscription rapide</p>
                    </a>

                    <a href="#" class="bg-yellow-50 hover:bg-yellow-100 border border-yellow-200 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
                        <svg class="w-8 h-8 text-yellow-600 mb-3 group-hover:text-yellow-700 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 0 0-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1z"/>
                        </svg>
                        <h4 class="font-bold text-gray-900 mb-1">Personnel</h4>
                        <p class="text-xs text-gray-600">Gestion de l'équipe</p>
                    </a>

                    <a href="#" class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
                        <svg class="w-8 h-8 text-indigo-600 mb-3 group-hover:text-indigo-700 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 10h-2V7c0-1.66-1.34-3-3-3s-3 1.34-3 3v3H8c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V12c0-1.1-.9-2-2-2zm-5-6c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM7 20v-8h10v8H7z"/>
                        </svg>
                        <h4 class="font-bold text-gray-900 mb-1">Classes</h4>
                        <p class="text-xs text-gray-600">Structure des cours</p>
                    </a>

                    <a href="#" class="bg-gray-100 hover:bg-gray-200 border border-gray-300 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
                        <svg class="w-8 h-8 text-gray-600 mb-3 group-hover:text-gray-700 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.43 12.98c.04-.32.07-.64.07-.98s-.03-.66-.07-.98l2.11-1.65c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.4-1.07-.75-1.68-.98l-.37-2.65c-.06-.32-.34-.56-.67-.56h-4c-.33 0-.6.24-.66.56l-.37 2.65c-.6.23-1.16.58-1.68.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.08.49.12.64l2.11 1.65c-.04.32-.07.65-.07.98s.03.66.07.98l-2.11 1.65c-.19.15-.24.42-.12.64l2 3.46c.12.22.39.31.61.22l2.49-1c.52.4 1.07.75 1.68.98l.37 2.65c.06.32.34.56.67.56h4c.33 0 .6-.24.66-.56l.37-2.65c.6-.23 1.16-.58 1.68-.98l2.49 1c.22.09.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.65zM12 15.5c-1.93 0-3.5-1.57-3.5-3.5s1.57-3.5 3.5-3.5 3.5 1.57 3.5 3.5-1.57 3.5-3.5 3.5z"/>
                        </svg>
                        <h4 class="font-bold text-gray-900 mb-1">Configuration</h4>
                        <p class="text-xs text-gray-600">Paramètres système</p>
                    </a>
                </div>
            </div>
        </div>

        <hr class="my-8 border-gray-200">

        {{-- Section Activités Récentes --}}
        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-6 border-b pb-3 flex items-center">
                <svg class="w-6 h-6 text-gray-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                </svg>
                Activités Récentes
            </h3>
            <div class="overflow-x-auto rounded-lg border border-gray-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Activité</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        {{-- Quelques exemples pour le style --}}
                        @php
                            $activites_recentes = $activites_recentes ?? [
                                ['description' => 'Inscription de l\'élève Ali Hassan.', 'utilisateur' => 'Admin (Vous)', 'date' => 'Il y a 2 minutes', 'statut' => 'Terminé'],
                                ['description' => 'Mise à jour des notes de la 2nde L.', 'utilisateur' => 'Prof. Fatima', 'date' => 'Il y a 1 heure', 'statut' => 'En cours'],
                                ['description' => 'Demande de congé pour le Censeur.', 'utilisateur' => 'M./Mme Proviseur', 'date' => 'Hier', 'statut' => 'En attente'],
                            ];
                        @endphp
                        @forelse ($activites_recentes as $activite)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $activite['description'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $activite['utilisateur'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activite['date'] }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $color = match ($activite['statut']) {
                                            'Terminé' => 'green',
                                            'En cours' => 'blue',
                                            'En attente' => 'yellow',
                                            default => 'gray',
                                        };
                                    @endphp
                                    <span class="inline-flex px-3 py-1 text-xs font-bold bg-{{ $color }}-100 text-{{ $color }}-700 rounded-full shadow-sm">
                                        {{ $activite['statut'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="4" class="px-6 py-6 text-sm text-gray-500">
                                    <p class="font-medium">✅ Tout est calme. Aucune activité récente à signaler.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- Footer Amélioré --}}
    <footer class="bg-white border-t border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <div class="flex items-center space-x-6">
                    <p class="text-sm text-gray-500 font-medium">© {{ date('Y') }} Lycée de Balbala</p>
                    <div class="flex space-x-1 items-center">
                        {{-- Référence subtile aux couleurs nationales (Djibouti) --}}
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
