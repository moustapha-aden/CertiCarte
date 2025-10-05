@extends('layouts.app')

@section('title', 'Détails de la Classe - ' . $classe->label)
@section('page-subtitle', 'Détails de la Classe')

@section('content')
    <x-breadcrumb :items="[['label' => 'Classes', 'url' => route('classes.index')], ['label' => $classe->label]]" />

    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-2xl p-8 mb-8 text-white shadow-2xl">
        <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-6 sm:space-y-0 sm:space-x-8">
            {{-- Class Icon --}}
            <div class="flex-shrink-0">
                <div class="w-32 h-32 bg-white/20 rounded-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                        </path>
                    </svg>
                </div>
            </div>

            {{-- Main Info --}}
            <div class="flex-grow text-center sm:text-left">
                <h1 class="text-4xl font-extrabold mb-2">{{ $classe->label }}</h1>
                <p class="text-xl text-indigo-100 mb-4">Année scolaire : {{ $classe->schoolYear->year ?? 'N/A' }}</p>
                <p class="text-lg text-indigo-200">
                    {{ $students->total() }} étudiant{{ $students->total() > 1 ? 's' : '' }}
                    inscrit{{ $students->total() > 1 ? 's' : '' }}
                </p>
            </div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3">
                <x-button onclick="openAttendanceModal({{ $classe->id }})" variant="outline" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>'
                    class="bg-white text-green-600 hover:bg-gray-100 border-green-500 hover:border-green-600 cursor-pointer">
                    Générer Liste d'Appel
                </x-button>
                <x-button href="{{ route('classes.index') }}" variant="secondary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>'>
                    Retour
                </x-button>
            </div>
        </div>
    </div>

    {{-- Class Details Card --}}
    <x-card title="Informations Détaillées"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>'
        class="mb-8">

        {{-- Information Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Class ID --}}
            <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide">ID Classe</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">#{{ $classe->id }}</p>
                <p class="text-sm text-gray-600 mt-1">Référence unique</p>
            </div>

            {{-- Class Name --}}
            <div class="p-6 bg-blue-50 rounded-xl border border-blue-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-blue-700 uppercase tracking-wide">Nom de la classe</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $classe->label }}</p>
            </div>

            {{-- School Year --}}
            <div class="p-6 bg-purple-50 rounded-xl border border-purple-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-purple-700 uppercase tracking-wide">Année scolaire</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $classe->schoolYear->year ?? 'N/A' }}</p>
            </div>

            {{-- Student Count --}}
            <div class="p-6 bg-indigo-50 rounded-xl border border-indigo-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-indigo-700 uppercase tracking-wide">Étudiants</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $students->total() }}</p>
                <p class="text-sm text-gray-600 mt-1">inscrit{{ $students->total() > 1 ? 's' : '' }}</p>
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
                    <h3 class="text-sm font-semibold text-green-700 uppercase tracking-wide">Créée le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $classe->created_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $classe->created_at->format('H:i') }}</p>
            </div>

            {{-- Last Modified --}}
            <div class="p-6 bg-yellow-50 rounded-xl border border-yellow-200 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-sm font-semibold text-yellow-700 uppercase tracking-wide">Modifiée le</h3>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $classe->updated_at->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600 mt-1">À {{ $classe->updated_at->format('H:i') }}</p>
            </div>
        </div>

        {{-- Additional Actions --}}
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0">
                <div class="text-sm text-gray-600">
                    <p>Dernière mise à jour : {{ $classe->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
                <div class="flex space-x-3">
                    <x-button href="{{ route('classes.edit', $classe) }}" variant="primary">
                        Modifier la classe
                    </x-button>
                    <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')">
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

    {{-- Students List Card --}}
    <x-card title="Étudiants de cette classe"
        icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>'>

        @if ($students->count() > 0)
            {{-- Students Table --}}
            <x-table :headers="[
                ['label' => 'Photo'],
                ['label' => 'Nom & Prénom'],
                ['label' => 'Matricule'],
                ['label' => 'Date de naissance'],
                ['label' => 'Genre'],
                ['label' => 'Actions', 'class' => 'text-center'],
            ]">
                @foreach ($students as $student)
                    <tr class="hover:bg-indigo-50/30 transition-colors">
                        {{-- Photo --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                                class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                        </td>

                        {{-- Name --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                        </td>

                        {{-- Matricule --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700 font-medium">{{ $student->matricule ?? 'N/A' }}</div>
                        </td>

                        {{-- Birth Date --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                        </td>

                        {{-- Gender --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->gender === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                {{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}
                            </span>
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center space-x-2">
                                <x-button href="{{ route('students.show', $student) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                    title="Voir les détails" />
                                <x-button href="{{ route('students.edit', $student) }}" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                    title="Modifier" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>

            {{-- Pagination --}}
            @if ($students->hasPages())
                <div class="mt-8">
                    {{ $students->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                    </path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucun étudiant dans cette classe</h3>
                <p class="text-sm text-gray-600 mb-6">Cette classe ne contient aucun étudiant pour le moment.</p>
                <x-button href="{{ route('students.create') }}" variant="primary" size="lg"
                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                    Ajouter un étudiant
                </x-button>
            </div>
        @endif
    </x-card>
    @push('scripts')
        {{-- Ajoutez ce code dans votre vue show.blade.php --}}

        {{-- Modal pour choisir le nombre de jours --}}
        <div id="attendanceModal"
            class="hidden fixed inset-0 bg-black bg-opacity-40 overflow-y-auto h-full w-full z-50
            backdrop-blur-sm shadow-inner">

            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-2xl bg-white">
                {{-- Header du Modal --}}
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <svg class="w-7 h-7 text-indigo-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                            </path>
                        </svg>
                        Liste d'Appel
                    </h3>
                    <button onclick="closeAttendanceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                {{-- Contenu du Modal --}}
                <div class="mb-6">
                    <p class="text-gray-700 mb-6 text-center">
                        Pour combien de jours souhaitez-vous générer la liste d'appel ?
                    </p>

                    {{-- Options de choix --}}
                    <div class="space-y-3">
                        {{-- Option 1 Jour --}}
                        <button onclick="generateAttendanceList(1)"
                            class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-xl border-2 border-blue-200 hover:border-blue-400 transition-all duration-200 group">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-lg font-bold text-gray-900">1 Jour</p>
                                    <p class="text-sm text-gray-600">Liste pour une seule journée</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-blue-500 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>

                        {{-- Option 2 Jours --}}
                        <button onclick="generateAttendanceList(2)"
                            class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 hover:from-purple-100 hover:to-pink-100 rounded-xl border-2 border-purple-200 hover:border-purple-400 transition-all duration-200 group">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-lg font-bold text-gray-900">2 Jours</p>
                                    <p class="text-sm text-gray-600">Liste pour deux journées consécutives</p>
                                </div>
                            </div>
                            <svg class="w-6 h-6 text-purple-500 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Footer du Modal --}}
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button onclick="closeAttendanceModal()"
                        class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors">
                        Annuler
                    </button>
                </div>
            </div>
        </div>

        @push('scripts')
            <script>
                let currentClasseId = null;

                // Ouvrir le modal
                function openAttendanceModal(classeId) {
                    currentClasseId = classeId;
                    document.getElementById('attendanceModal').classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Empêcher le scroll
                }

                // Fermer le modal
                function closeAttendanceModal() {
                    document.getElementById('attendanceModal').classList.add('hidden');
                    document.body.style.overflow = 'auto'; // Réactiver le scroll
                    currentClasseId = null;
                }

                // Générer la liste d'appel
                function generateAttendanceList(days) {
                    if (currentClasseId && (days === 1 || days === 2)) {
                        const url = `{{ url('classes') }}/${currentClasseId}/liste-appel?days=${days}`;

                        // Fermer le modal
                        closeAttendanceModal();

                        // Rediriger vers la génération de la liste
                        // window.location.href = url;
                        window.open(url, '_blank');

                        // Alternative : Ouvrir dans un nouvel onglet
                        // window.open(url, '_blank');
                    }
                }

                // Fermer le modal en cliquant à l'extérieur
                document.getElementById('attendanceModal')?.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeAttendanceModal();
                    }
                });

                // Fermer le modal avec la touche Escape
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !document.getElementById('attendanceModal').classList.contains('hidden')) {
                        closeAttendanceModal();
                    }
                });
            </script>
        @endpush
    @endpush
@endsection
