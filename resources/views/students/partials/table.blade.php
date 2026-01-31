@if ($students->count() > 0)
    <x-table :headers="[
        ['label' => 'Photo'],
        ['field' => 'name', 'label' => 'Nom & Prénom', 'sortable' => true, 'route' => 'students.index'],
        ['field' => 'matricule', 'label' => 'Matricule', 'sortable' => true, 'route' => 'students.index'],
        ['label' => 'Classe'],
        [
            'field' => 'date_of_birth',
            'label' => 'Date de naissance',
            'sortable' => true,
            'route' => 'students.index',
        ],
        ['field' => 'gender', 'label' => 'Genre', 'sortable' => true, 'route' => 'students.index'],
        ['label' => 'Actions', 'class' => 'text-center'],
    ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$students"
        :itemLabel="'étudiants'">

        @foreach ($students as $student)
            <tr class="hover:bg-indigo-50/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <img src="{{ $student->avatar_url }}" alt="{{ $student->name }}"
                        class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-semibold text-gray-900">{{ $student->name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-700 font-medium">{{ $student->matricule ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($student->classe)
                        <a href="{{ route('classes.show', $student->classe) }}"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 hover:text-blue-900 transition-colors duration-200 cursor-pointer"
                            title="Voir les détails de la classe {{ $student->classe->label }}">
                            {{ $student->classe->label }}
                        </a>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            N/A
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $student->gender === 'M' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                        {{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <div class="flex items-center justify-center space-x-1">
                        @can('view_students')
                            <x-button href="{{ route('students.show', $student) }}" variant="ghost" size="sm"
                                title="Voir les détails"
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 border-blue-200 hover:border-blue-300" />
                        @endcan
                        @can('edit_students')
                            <x-button href="{{ route('students.edit', $student) }}" variant="ghost" size="sm"
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                title="Modifier"
                                class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border-indigo-200 hover:border-indigo-300" />
                        @endcan
                        @can('delete_students')
                            <form method="POST" action="{{ route('students.destroy', $student) }}"
                                class="inline-block"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer l\'étudiant {{ $student->name }} ?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                    title="Supprimer"
                                    class="text-red-600 hover:text-red-800 hover:bg-red-50 border-red-200 hover:border-red-300" />
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    @if ($students->hasPages())
        <div class="mt-8">
            <x-pagination :paginator="$students" :itemLabel="'étudiants'" />
        </div>
    @endif
@else
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
            </path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">
            @if (request()->hasAny(['search', 'classe_id', 'year_id']))
                Aucun étudiant trouvé
            @else
                Aucun étudiant enregistré
            @endif
        </h3>
        <p class="text-sm text-gray-600 mb-6">
            @if (request()->hasAny(['search', 'classe_id', 'year_id']))
                Aucun étudiant ne correspond aux critères de recherche.
            @else
                Commencez par ajouter votre premier étudiant.
            @endif
        </p>
        @can('create_students')
            <x-button href="{{ route('students.create') }}" variant="primary" size="lg"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                Ajouter un étudiant
            </x-button>
        @endcan
    </div>
@endif
