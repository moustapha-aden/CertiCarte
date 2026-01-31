@if ($classes->count() > 0)
    <x-table :headers="[
        ['field' => 'label', 'label' => 'Nom de la Classe', 'sortable' => true, 'route' => 'classes.index'],
        ['label' => 'Année Scolaire'],
        [
            'field' => 'students_count',
            'label' => 'Nombre d\'Élèves',
            'sortable' => true,
            'route' => 'classes.index',
        ],
        [
            'field' => 'created_at',
            'label' => 'Date de Création',
            'sortable' => true,
            'route' => 'classes.index',
        ],
        ['label' => 'Actions', 'class' => 'text-center'],
    ]" :currentSort="$sortBy" :currentOrder="$sortOrder" :queryParams="request()->query()" :pagination="$classes"
        :itemLabel="'classes'">

        @foreach ($classes as $classe)
            <tr class="hover:bg-indigo-50/30 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-900">{{ $classe->label }}</div>
                            <div class="text-sm text-gray-500">ID: #{{ $classe->id }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $classe->schoolYear->year ?? 'N/A' }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @php $count = $classe->students_count ?? $classe->students->count(); @endphp
                    <div class="flex items-center">
                        <span class="text-2xl font-bold text-indigo-600">{{ $count }}</span>
                        <span class="text-sm text-gray-500 ml-2">élève{{ $count > 1 ? 's' : '' }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    <div>{{ $classe->created_at->format('d/m/Y') }}</div>
                    <div class="text-xs text-gray-500">{{ $classe->created_at->format('H:i') }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                    <div class="flex items-center justify-center space-x-2">
                        @can('view_classes')
                            <x-button href="{{ route('classes.show', $classe) }}" variant="ghost" size="sm"
                                title="Voir les détails"
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>'
                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 border-blue-200 hover:border-blue-300" />
                        @endcan
                        @can('edit_classes')
                            <x-button href="{{ route('classes.edit', $classe) }}" variant="ghost" size="sm"
                                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>'
                                title="Modifier"
                                class="text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 border-indigo-200 hover:border-indigo-300" />
                        @endcan
                        @can('delete_classes')
                            <form method="POST" action="{{ route('classes.destroy', $classe) }}" class="inline-block"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer la classe {{ $classe->label }} ?')">
                                @csrf
                                @method('DELETE')
                                <x-button type="submit" variant="ghost" size="sm"
                                    icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>'
                                    title="Supprimer" class="text-red-600 hover:text-red-800 hover:bg-red-50" />
                            </form>
                        @endcan
                    </div>
                </td>
            </tr>
        @endforeach
    </x-table>

    @if ($classes->hasPages())
        <div class="mt-8">
            <x-pagination :paginator="$classes" :itemLabel="'classes'" />
        </div>
    @endif
@else
    <div class="text-center py-12">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
            </path>
        </svg>
        <h3 class="text-lg font-semibold text-gray-700 mb-2">
            @if (request()->hasAny(['search', 'year_id']))
                Aucune classe trouvée
            @else
                Aucune classe enregistrée
            @endif
        </h3>
        <p class="text-sm text-gray-600 mb-6">
            @if (request()->hasAny(['search', 'year_id']))
                Aucune classe ne correspond aux critères de recherche.
            @else
                Commencez par créer votre première classe.
            @endif
        </p>
        @can('create_classes')
            <x-button href="{{ route('classes.create') }}" variant="primary" size="lg"
                icon='<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>'>
                Créer une nouvelle classe
            </x-button>
        @endcan
    </div>
@endif
