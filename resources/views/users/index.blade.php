@extends('layouts.app')

@section('title', 'Gestion des Utilisateurs')
@section('page-subtitle', 'Gestion des Utilisateurs')

@section('content')
    <x-breadcrumb :items="[['label' => 'Utilisateurs']]" />

    {{-- Single Card: Complete Users Management --}}
    <x-card class="mb-8">
        {{-- Card Header: Title and Add Button --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div class="flex items-center space-x-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
                <h1 class="text-2xl font-bold text-gray-800">Catalogue des Utilisateurs</h1>
            </div>

            <x-button href="{{ route('users.create') }}" variant="primary" size="md"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                Nouvel Utilisateur
            </x-button>
        </div>

        {{-- Horizontal Divider --}}
        <div class="border-t border-gray-200 mb-6"></div>

        {{-- Search Section --}}
        <form method="GET" action="{{ route('users.index') }}" class="mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- Search Row --}}
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 flex-1">
                    {{-- Search Filter --}}
                    <div class="relative flex-1 max-w-md">
                        <input type="search" name="q" placeholder="Rechercher par nom, email ou rôle..."
                            value="{{ request('q') }}"
                            class="w-full px-3 py-2 pl-10 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>

                    {{-- Role Filter --}}
                    <div class="flex items-center space-x-2">
                        <label for="role" class="text-sm font-medium text-gray-700 whitespace-nowrap">Rôle:</label>
                        <select name="role" id="role" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 min-w-[150px]">
                            <option value="">Tous les rôles</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrateur
                            </option>
                            <option value="secretary" {{ request('role') == 'secretary' ? 'selected' : '' }}>Secrétaire
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Reset Button --}}
                @if (request()->hasAny(['q', 'role']))
                    <div class="flex justify-end">
                        <x-button type="button" variant="secondary" size="md"
                            onclick="window.location.href='{{ route('users.index') }}'"
                            icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>'
                            class="bg-red-600 hover:bg-red-700 text-white">
                            Réinitialiser les filtres
                        </x-button>
                    </div>
                @endif
            </div>
        </form>

        {{-- Users Table Section --}}
        @if ($users->count() > 0)
            <x-table :headers="[
                ['label' => 'Avatar'],
                ['label' => 'Nom & Email'],
                ['label' => 'Rôle'],
                ['label' => 'Date de création'],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]" :pagination="$users" :itemLabel="'utilisateurs'">
                @foreach ($users as $user)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        {{-- Avatar --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div
                                class="w-10 h-10 bg-gradient-to-tr from-indigo-500 to-pink-500 rounded-full flex items-center justify-center">
                                <span
                                    class="text-white text-sm font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </td>

                        {{-- Name & Email --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                            </div>
                        </td>

                        {{-- Role --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ $user->role === 'admin' ? 'Administrateur' : 'Secrétaire' }}
                            </span>
                        </td>

                        {{-- Creation Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            <div>{{ $user->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <x-button href="{{ route('users.show', $user) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                    title="Voir les détails" />

                                <x-button href="{{ route('users.edit', $user) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                    title="Modifier" />

                                <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline-block"
                                    onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'utilisateur {{ $user->name }} ?')">
                                    @csrf
                                    @method('DELETE')
                                    <x-button type="submit" variant="ghost" size="sm"
                                        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                        title="Supprimer" class="text-red-600 hover:text-red-800 hover:bg-red-50" />
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">
                    @if (request()->hasAny(['q', 'role']))
                        Aucun utilisateur trouvé
                    @else
                        Aucun utilisateur enregistré
                    @endif
                </h3>
                <p class="text-sm text-gray-600 mb-6">
                    @if (request()->hasAny(['q', 'role']))
                        Aucun utilisateur ne correspond aux critères de recherche.
                    @else
                        Commencez par ajouter votre premier utilisateur.
                    @endif
                </p>
                <x-button href="{{ route('users.create') }}" variant="primary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                    Ajouter un utilisateur
                </x-button>
            </div>
        @endif
    </x-card>
@endsection
