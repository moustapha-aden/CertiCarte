@extends('layouts.app')

@section('title', 'Tableau de Bord')
@section('page-subtitle', 'Tableau de Bord')

@section('content')
    {{-- Welcome Section --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- Welcome Icon --}}
            <div class="flex-shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                </div>
            </div>

            {{-- Welcome Text --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">Bienvenue sur le Tableau de Bord</h1>
                <p class="text-xl text-indigo-100 mb-4">Lycée de Balbala</p>
                <p class="text-lg text-indigo-200">
                    Gérez efficacement vos classes et étudiants
                </p>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <x-button href="{{ route('classes.create') }}" variant="outline" size="lg" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>' class="bg-white text-indigo-600 hover:bg-gray-100">
                    Nouvelle Classe
                </x-button>
                <x-button href="{{ route('students.create') }}" variant="outline" size="lg" icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>' class="bg-white text-indigo-600 hover:bg-gray-100">
                    Nouvel Étudiant
                </x-button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Classes --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Classes</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalClasses ?? 0 }}</p>
                </div>
            </div>
        </x-card>

        {{-- Total Students --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Étudiants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                </div>
            </div>
        </x-card>

        {{-- Active School Year --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Année Active</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $currentYear ?? 'N/A' }}</p>
                </div>
            </div>
        </x-card>

        {{-- Average per Class --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Moy. par Classe</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $averageStudentsPerClass ?? 0 }}</p>
                </div>
            </div>
        </x-card>
    </div>

    {{-- Quick Actions Card --}}
    <x-card title="Actions Rapides" 
            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>'
            class="mb-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Manage Classes --}}
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-800">Gestion des Classes</h3>
                </div>
                <p class="text-sm text-blue-600 mb-4">Créez et gérez les classes de votre établissement</p>
                <div class="flex space-x-2">
                    <x-button href="{{ route('classes.index') }}" variant="primary" size="sm">
                        Voir toutes
                    </x-button>
                    <x-button href="{{ route('classes.create') }}" variant="outline" size="sm">
                        Créer
                    </x-button>
                </div>
            </div>

            {{-- Manage Students --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-green-800">Gestion des Étudiants</h3>
                </div>
                <p class="text-sm text-green-600 mb-4">Inscrivez et suivez vos étudiants</p>
                <div class="flex space-x-2">
                    <x-button href="{{ route('students.index') }}" variant="primary" size="sm">
                        Voir tous
                    </x-button>
                    <x-button href="{{ route('students.create') }}" variant="outline" size="sm">
                        Inscrire
                    </x-button>
                </div>
            </div>

            {{-- Reports --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-purple-800">Rapports</h3>
                </div>
                <p class="text-sm text-purple-600 mb-4">Consultez les statistiques et rapports</p>
                <div class="flex space-x-2">
                    <x-button href="#" variant="primary" size="sm">
                        Statistiques
                    </x-button>
                    <x-button href="#" variant="outline" size="sm">
                        Exporter
                    </x-button>
                </div>
            </div>
        </div>
    </x-card>

    {{-- Recent Activity Card --}}
    <x-card title="Activité Récente" 
            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
            class="mb-8">
        
        <div class="space-y-4">
            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Nouvelle classe créée</p>
                    <p class="text-xs text-gray-500">Il y a 2 heures</p>
                </div>
            </div>

            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Nouvel étudiant inscrit</p>
                    <p class="text-xs text-gray-500">Il y a 4 heures</p>
                </div>
            </div>

<<<<<<< HEAD
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

                    <a href="/dashboard/classes" class="bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-xl p-4 text-left transition-all duration-200 hover:shadow-lg transform hover:-translate-y-0.5 group">
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
=======
            <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">Classe modifiée</p>
                    <p class="text-xs text-gray-500">Il y a 1 jour</p>
>>>>>>> 25ec0b94b233c9e4a18eaee46f88572a104b355c
                </div>
            </div>
        </div>
    </x-card>
@endsection