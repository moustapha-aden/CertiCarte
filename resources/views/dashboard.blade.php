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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                </div>
            </div>

            {{-- Welcome Text --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">Bienvenue sur le Tableau de Bord</h1>
                <p class="text-xl text-indigo-100 mb-4">Lycée Ahmed Farah Ali</p>
                <p class="text-lg text-indigo-200">
                    Gérez efficacement vos classes et étudiants
                </p>
            </div>

            {{-- Quick Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <x-button href="{{ route('classes.create') }}" variant="outline" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
                    class="bg-white text-indigo-600 hover:bg-gray-100">
                    Nouvelle Classe
                </x-button>
                <x-button href="{{ route('students.create') }}" variant="outline" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'
                    class="bg-white text-indigo-600 hover:bg-gray-100">
                    Nouvel Étudiant
                </x-button>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Utilisateurs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalUsers ?? 0 }}</p>
                </div>
            </div>
        </x-card>

        {{-- Total Classes --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Étudiants</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalStudents ?? 0 }}</p>
                </div>
            </div>
        </x-card>

        {{-- Current School Year --}}
        <x-card class="hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Année Scolaire</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $currentYearLabel }}</p>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
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

            {{-- Manage Users --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-purple-800">Gestion des Utilisateurs</h3>
                </div>
                <p class="text-sm text-purple-600 mb-4">Gérez les comptes administrateurs et secrétaires</p>
                <div class="flex space-x-2">
                    <x-button href="{{ route('users.index') }}" variant="primary" size="sm">
                        Voir tous
                    </x-button>
                    <x-button href="{{ route('users.create') }}" variant="outline" size="sm">
                        Créer
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
            {{-- Today's Activity --}}
            @if ($recentActivity['new_students_today'] > 0)
                <div class="flex items-center space-x-3 p-4 bg-green-50 rounded-lg border border-green-200">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $recentActivity['new_students_today'] }}
                            nouvel(le)(s) étudiant(e)(s) inscrit(e)(s) aujourd'hui</p>
                        <p class="text-xs text-gray-500">Aujourd'hui</p>
                    </div>
                </div>
            @endif

            {{-- This Week's Classes --}}
            @if ($recentActivity['new_classes_this_week'] > 0)
                <div class="flex items-center space-x-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">{{ $recentActivity['new_classes_this_week'] }}
                            nouvelle(s) classe(s) créée(s) cette semaine</p>
                        <p class="text-xs text-gray-500">Cette semaine</p>
                    </div>
                </div>
            @endif

            {{-- Monthly Registrations --}}
            @if ($recentActivity['total_registrations_this_month'] > 0)
                <div class="flex items-center space-x-3 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">
                            {{ $recentActivity['total_registrations_this_month'] }} inscription(s) ce mois</p>
                        <p class="text-xs text-gray-500">Ce mois-ci</p>
                    </div>
                </div>
            @endif

            {{-- No Recent Activity --}}
            @if (
                $recentActivity['new_students_today'] == 0 &&
                    $recentActivity['new_classes_this_week'] == 0 &&
                    $recentActivity['total_registrations_this_month'] == 0)
                <div class="text-center py-8">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Aucune activité récente</p>
                    <p class="text-sm text-gray-400 mt-1">Les nouvelles inscriptions et créations apparaîtront ici</p>
                </div>
            @endif
        </div>
    </x-card>
@endsection
