{{-- Table Component --}}
<div class="overflow-hidden rounded-xl border border-gray-200 shadow-lg">
    <table class="min-w-full divide-y divide-gray-200">
        {{-- Table Header --}}
        @if (isset($headers))
            <thead class="bg-indigo-50">
                <tr>
                    @foreach ($headers as $header)
                        @if (isset($header['sortable']) && $header['sortable'])
                            {{-- Sortable Header --}}
                            <x-sortable-header :field="$header['field']" :label="$header['label']" :currentSort="$currentSort ?? ''" :currentOrder="$currentOrder ?? 'asc'"
                                :route="$header['route'] ?? ''" :queryParams="$queryParams ?? []" :routeParams="$header['routeParams'] ?? []" :class="$header['class'] ?? ''" />
                        @else
                            {{-- Regular Header --}}
                            @php
                                $textAlign = 'text-left';
                                if (isset($header['class'])) {
                                    if (str_contains($header['class'], 'text-center')) {
                                        $textAlign = 'text-center';
                                    } elseif (str_contains($header['class'], 'text-right')) {
                                        $textAlign = 'text-right';
                                    }
                                }
                                $headerClass = str_replace(
                                    ['text-left', 'text-center', 'text-right'],
                                    '',
                                    $header['class'] ?? '',
                                );
                            @endphp
                            <th
                                class="px-6 py-3 {{ $textAlign }} text-xs font-medium text-gray-500 uppercase tracking-wider {{ trim($headerClass) }}">
                                {{ $header['label'] ?? $header }}
                            </th>
                        @endif
                    @endforeach
                </tr>
            </thead>
        @endif

        {{-- Table Body --}}
        <tbody class="bg-white divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>

    {{-- Empty State --}}
    @if (isset($empty) && $empty)
        <div class="text-center py-12 bg-gray-50">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                </path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Aucune donnée disponible</h3>
            <p class="text-sm text-gray-600 mb-6">Il n'y a pas de données à afficher pour le moment.</p>
            @if (isset($emptyAction))
                {!! $emptyAction !!}
            @endif
        </div>
    @endif
</div>

{{-- Pagination --}}
@if (isset($pagination) && $pagination)
    <x-pagination :paginator="$pagination" :itemLabel="$itemLabel ?? 'éléments'" />
@endif
