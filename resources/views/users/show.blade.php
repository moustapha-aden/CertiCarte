@extends('layouts.app')

@section('title', 'Détails du Membre du Personnel - ' . $user->name)
@section('page-subtitle', 'Détails du Personnel')

@section('content')
    <x-breadcrumb :items="[['label' => 'Personnel', 'url' => route('users.index')], ['label' => $user->name]]" />

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- User Avatar --}}
            <div class="shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <span class="text-white text-4xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            </div>

            {{-- Main Info --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">{{ $user->name }}</h1>
                <p class="text-xl text-indigo-100 mb-2">{{ $user->email }}</p>
                <p class="text-lg text-indigo-200">
                    {{ ucfirst($user->roles->first()->name ?? 'Personnel') }} • Membre depuis
                    {{ $user->created_at->format('Y') }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
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
            {{-- User ID --}}
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">ID Utilisateur</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">#{{ $user->id }}</p>
                <p class="text-sm text-gray-600 mt-1">Référence unique</p>
            </div>

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
                <p class="text-2xl font-bold text-gray-900">{{ ucfirst($user->roles->first()->name ?? 'Personnel') }}</p>
            </div>

            {{-- Creation Date --}}
            <div class="p-6 bg-green-50 rounded-xl border border-green-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Créé le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $user->created_at->format('H:i') }}</p>
            </div>
        </div>

        {{-- Additional Actions --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-600">
                    <p>Dernière mise à jour : {{ $user->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    @can('edit_users')
                        <x-button href="{{ route('users.edit', $user) }}" variant="primary">
                            Modifier le membre du personnel
                        </x-button>
                    @endcan
                    @can('delete_users')
                        <form method="POST" action="{{ route('users.destroy', $user) }}" class="inline-block"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre du personnel ?')">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" variant="danger">
                                Supprimer
                            </x-button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </x-card>

    {{-- Permission Management Card --}}
    <x-card title="Gestion des Permissions"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>'
        class="mb-8">

        {{-- Description --}}
        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <div class="flex items-start space-x-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Gestion des Permissions</h3>
                    <p class="text-sm text-blue-700 mt-1">
                        Activez ou désactivez les permissions pour {{ $user->name }}.
                        Les changements sont appliqués immédiatement.
                    </p>
                </div>
            </div>
        </div>

        {{-- Permissions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @php
                $allPermissions = \Spatie\Permission\Models\Permission::all();
            @endphp

            @foreach ($allPermissions as $permission)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex-1">
                        <label class="text-sm font-medium text-gray-700 cursor-pointer"
                            for="permission_{{ $permission->id }}">
                            {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                        </label>
                        <p class="text-xs text-gray-500 mt-1">
                            @switch($permission->name)
                                @case('view_classes')
                                    Permet de consulter la liste des classes
                                @break

                                @case('create_classes')
                                    Permet de créer de nouvelles classes
                                @break

                                @case('edit_classes')
                                    Permet de modifier les classes existantes
                                @break

                                @case('delete_classes')
                                    Permet de supprimer des classes
                                @break

                                @case('view_students')
                                    Permet de consulter la liste des étudiants
                                @break

                                @case('create_students')
                                    Permet de créer de nouveaux étudiants
                                @break

                                @case('edit_students')
                                    Permet de modifier les étudiants existants
                                @break

                                @case('delete_students')
                                    Permet de supprimer des étudiants
                                @break

                                @case('generate_certificates')
                                    Permet de générer des certificats PDF
                                @break

                                @case('generate_cards')
                                    Permet de générer des cartes d'identité étudiant
                                @break

                                @case('generate_attendance_lists')
                                    Permet de générer des listes d'appel
                                @break

                                @case('import_students')
                                    Permet d'importer des étudiants depuis Excel/CSV
                                @break

                                @default
                                    Permission système
                            @endswitch
                        </p>
                    </div>
                    <div class="ml-4">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer permission-toggle"
                                id="permission_{{ $permission->id }}" data-user-id="{{ $user->id }}"
                                data-permission="{{ $permission->name }}"
                                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                            <div
                                class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                            </div>
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Current Permissions Summary --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-700">Permissions actives:</span>
                <div class="flex flex-wrap gap-2">
                    @if ($user->getAllPermissions()->count() > 0)
                        @foreach ($user->getAllPermissions() as $permission)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                {{ ucfirst(str_replace('_', ' ', $permission->name)) }}
                            </span>
                        @endforeach
                    @else
                        <span class="text-sm text-gray-500 italic">Aucune permission assignée</span>
                    @endif
                </div>
            </div>
        </div>
    </x-card>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const permissionToggles = document.querySelectorAll('.permission-toggle');

            permissionToggles.forEach(toggle => {
                toggle.addEventListener('change', function() {
                    const userId = this.dataset.userId;
                    const permission = this.dataset.permission;
                    const isChecked = this.checked;

                    // Get current permissions
                    const currentPermissions = Array.from(document.querySelectorAll(
                            `[data-user-id="${userId}"]:checked`))
                        .map(el => el.dataset.permission);

                    // Update permissions based on toggle state
                    let updatedPermissions = [...currentPermissions];
                    if (isChecked && !updatedPermissions.includes(permission)) {
                        updatedPermissions.push(permission);
                    } else if (!isChecked && updatedPermissions.includes(permission)) {
                        updatedPermissions = updatedPermissions.filter(p => p !== permission);
                    }

                    // Send AJAX request
                    fetch(`/dashboard/users/${userId}/permissions`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                permissions: updatedPermissions
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show success message
                                showNotification('Permissions mises à jour avec succès',
                                    'success');

                                // Reload page to update permission summary
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            } else {
                                // Revert toggle state
                                this.checked = !isChecked;
                                showNotification(data.message ||
                                    'Erreur lors de la mise à jour', 'error');
                            }
                        })
                        .catch(error => {
                            // Revert toggle state
                            this.checked = !isChecked;
                            showNotification('Erreur de connexion', 'error');
                            console.error('Error:', error);
                        });
                });
            });
        });

        function showNotification(message, type) {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
@endpush
