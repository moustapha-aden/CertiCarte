{{-- Button Component --}}
@php
    $baseClasses =
        'inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $variantClasses = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 focus:ring-indigo-500',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-500',
        'info' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
        'outline' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-indigo-500',
        'ghost' => 'bg-transparent text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
    ];

    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-base',
        'xl' => 'px-8 py-4 text-lg',
    ];

    $classes =
        $baseClasses .
        ' ' .
        ($variantClasses[$variant ?? 'primary'] ?? $variantClasses['primary']) .
        ' ' .
        ($sizeClasses[$size ?? 'md'] ?? $sizeClasses['md']);

    if (isset($class)) {
        $classes .= ' ' . $class;
    }
@endphp

@if (isset($href))
    {{-- Link Button --}}
    <a href="{{ $href }}" class="{{ $classes }}"
        @if (isset($target)) target="{{ $target }}" @endif
        @if (isset($title)) title="{{ $title }}" @endif>
        @if (isset($icon))
            <svg class="w-4 h-4 {{ isset($iconPosition) && $iconPosition === 'right' ? 'ml-2' : 'mr-2' }}" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        @endif
        {{ $slot }}
    </a>
@elseif(isset($form))
    {{-- Form Submit Button --}}
    <button type="submit" form="{{ $form }}" class="{{ $classes }}"
        @if (isset($disabled) && $disabled) disabled @endif
        @if (isset($title)) title="{{ $title }}" @endif>
        @if (isset($icon))
            <svg class="w-4 h-4 {{ isset($iconPosition) && $iconPosition === 'right' ? 'ml-2' : 'mr-2' }}" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        @endif
        {{ $slot }}
    </button>
@else
    {{-- Regular Button --}}
    <button type="{{ $type ?? 'button' }}" class="{{ $classes }}"
        @if (isset($onclick)) onclick="{{ $onclick }}" @endif
        @if (isset($disabled) && $disabled) disabled @endif
        @if (isset($title)) title="{{ $title }}" @endif>
        @if (isset($icon))
            <svg class="w-4 h-4 {{ isset($iconPosition) && $iconPosition === 'right' ? 'ml-2' : 'mr-2' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                {!! $icon !!}
            </svg>
        @endif
        {{ $slot }}
    </button>
@endif
