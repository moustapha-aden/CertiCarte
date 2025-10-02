@extends('layouts.app')

@section('title', 'Détails de l\'Utilisateur - ' . $user->name)
@section('page-subtitle', 'Détails de l\'Utilisateur')

@section('content')
    <x-breadcrumb :items="[['label' => 'Utilisateurs', 'url' => route('users.index')], ['label' => $user->name]]" />

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- User Avatar --}}
            <div class="flex-shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-white text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            </div>

            {{-- Main Info --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">{{ $user->name }}</h1>
                <p class="text-xl text-indigo-100 mb-2">{{ $user->email }}</p>
                <p class="text-lg text-indigo-200">
                    {{ $user->role === 'admin' ? 'Administrateur' : 'Secrétaire' }} • Membre depuis
                    {{ $user->created_at->format('Y') }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <x-button href="{{ route('users.edit', $user) }}" variant="outline" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                    class="bg-white text-indigo-600 hover:bg-gray-100">
                    Modifier
                </x-button>
                <x-button href="{{ route('users.index') }}" variant="secondary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>'>
                    Retour
                </x-button>
            </div>
        </div>
    </div>

    {{-- User Details Card --}}
    <x-card title="Informations Détaillées"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'
        class="mb-8">

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- User Name --}}
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Nom complet</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->name }}</p>
            </div>

            {{-- Email --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Email</h3>
                </div>
                <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
            </div>

            {{-- Role --}}
            <div class="p-6 bg-indigo-50 rounded-xl border border-indigo-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Rôle</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->role === 'admin' ? 'Administrateur' : 'Secrétaire' }}
                </p>
            </div>

            {{-- User ID --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">ID Utilisateur</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">#{{ $user->id }}</p>
                <p class="text-sm text-gray-600 mt-1">Référence unique</p>
            </div>

            {{-- Creation Date --}}
            <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide">Créé le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $user->created_at->format('H:i') }}</p>
            </div>

            {{-- Last Modified --}}
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">Modifié le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->updated_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $user->updated_at->format('H:i') }}</p>
            </div>
        </div>

        {{-- Additional Actions --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-600">
                    <p>Dernière mise à jour : {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-button href="{{ route('users.edit', $user) }}" variant="primary">
                        Modifier l'utilisateur
                    </x-button>
                    <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline-block"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')">
                        @csrf
                        @method('DELETE')
                        <x-button type="submit" variant="danger">
                            Supprimer
                        </x-button>
                    </form>
                </div>
            </div>
        </div>
    </x-card>
@endsection
