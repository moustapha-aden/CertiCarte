{{-- Pagination Component --}}
@if ($paginator->hasPages())
    <div class="mt-8">
        <div class="flex flex-col items-center space-y-4">
            {{-- Pagination Info --}}
            <div class="text-sm text-gray-600 bg-gray-50 px-4 py-2 rounded-lg border">
                <span class="font-medium text-gray-800">Affichage de</span>
                <span class="font-semibold text-indigo-600">{{ $paginator->firstItem() }}</span>
                <span class="font-medium text-gray-800">à</span>
                <span class="font-semibold text-indigo-600">{{ $paginator->lastItem() }}</span>
                <span class="font-medium text-gray-800">sur</span>
                <span class="font-semibold text-indigo-600">{{ $paginator->total() }}</span>
                <span class="font-medium text-gray-800">{{ $itemLabel ?? 'éléments' }}</span>
            </div>

            {{-- Pagination Links --}}
            <div class="flex justify-center">
                {{ $paginator->appends(request()->query())->links('pagination::custom') }}
            </div>
        </div>
    </div>
@endif
