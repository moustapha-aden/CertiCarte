{{-- Modal Card Component --}}
@props([
    'title' => '',
    'subtitle' => '',
    'icon' => '',
    'variant' => 'default',
])

@php
    $variantClasses = [
        'default' => 'bg-white border-gray-200',
        'primary' => 'bg-gradient-to-br from-indigo-50 to-blue-50 border-indigo-200',
        'success' => 'bg-gradient-to-br from-green-50 to-emerald-50 border-green-200',
        'warning' => 'bg-gradient-to-br from-yellow-50 to-orange-50 border-yellow-200',
        'danger' => 'bg-gradient-to-br from-red-50 to-pink-50 border-red-200',
    ];

    $variantClass = $variantClasses[$variant] ?? $variantClasses['default'];
@endphp

<div class="rounded-xl border-2 {{ $variantClass }} p-5 hover:shadow-lg transition-all duration-300 group cursor-pointer {{ $class ?? '' }}"
    @if (isset($onclick)) onclick="{{ $onclick }}" @endif
    @if (isset($dataAction)) data-action="{{ $dataAction }}" @endif
    @if (isset($dataDays)) data-days="{{ $dataDays }}" @endif>
    {{-- Card Header --}}
    @if ($title || $icon)
        <div class="flex items-center space-x-4 mb-4">
            @if ($icon)
                <div
                    class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        {!! $icon !!}
                    </svg>
                </div>
            @endif

            <div class="flex-1">
                @if ($title)
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $title }}
                    </h3>
                @endif
                @if ($subtitle)
                    <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                @endif
            </div>

            {{-- Arrow Icon --}}
            <svg class="w-6 h-6 text-indigo-500 group-hover:translate-x-1 transition-transform duration-200"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
    @endif

    {{-- Card Content --}}
    <div class="space-y-3">
        {{ $slot }}
    </div>
</div>
