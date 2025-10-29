@props([
    'label' => 'Label',
    'value' => 0,
    'color' => 'blue',
    'icon' => null,
])

@php
    $baseColors = [
        'blue' => [
            'bg' => 'from-blue-50 via-white to-blue-100',
            'border' => 'border-blue-200',
            'text' => 'text-blue-700',
            'accent' => 'text-blue-900',
            'iconBg' => 'bg-blue-100 border-blue-200',
        ],
        'green' => [
            'bg' => 'from-green-50 via-white to-green-100',
            'border' => 'border-green-200',
            'text' => 'text-green-700',
            'accent' => 'text-green-900',
            'iconBg' => 'bg-green-100 border-green-200',
        ],
        'red' => [
            'bg' => 'from-red-50 via-white to-red-100',
            'border' => 'border-red-200',
            'text' => 'text-red-700',
            'accent' => 'text-red-900',
            'iconBg' => 'bg-red-100 border-red-200',
        ],
        'yellow' => [
            'bg' => 'from-yellow-50 via-white to-yellow-100',
            'border' => 'border-yellow-200',
            'text' => 'text-yellow-700',
            'accent' => 'text-yellow-900',
            'iconBg' => 'bg-yellow-100 border-yellow-200',
        ],
        'purple' => [
            'bg' => 'from-purple-50 via-white to-purple-100',
            'border' => 'border-purple-200',
            'text' => 'text-purple-700',
            'accent' => 'text-purple-900',
            'iconBg' => 'bg-purple-100 border-purple-200',
        ],
        'gray' => [
            'bg' => 'from-gray-50 via-white to-gray-100',
            'border' => 'border-gray-200',
            'text' => 'text-gray-700',
            'accent' => 'text-gray-900',
            'iconBg' => 'bg-gray-100 border-gray-200',
        ],
    ];
    $c = $baseColors[$color];
@endphp

<div
    class="relative bg-gradient-to-br {{ $c['bg'] }} border {{ $c['border'] }} rounded-2xl p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-3">
                <div
                    class="w-8 h-8 {{ $c['iconBg'] }} text-{{ $color }}-600 rounded-lg flex items-center justify-center mr-2">
                    {!! $icon !!}
                </div>
                <p class="text-sm font-semibold {{ $c['text'] }} tracking-wide uppercase">
                    {{ $label }}
                </p>
            </div>
            <p class="text-2xl font-extrabold {{ $c['accent'] }} leading-tight">
                {{ $value }}
            </p>
        </div>
    </div>
</div>
