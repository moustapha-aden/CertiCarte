{{-- Form Input Component --}}
@php
    $inputId = $id ?? ($name ?? 'input-' . uniqid());
    $hasError = $errors->has($name ?? '');
    $type = $type ?? 'text';
    $baseClasses =
        'block w-full px-3 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-0 transition duration-200';
    $errorClasses = $hasError
        ? 'border-red-300 focus:border-red-500 focus:ring-red-500'
        : 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500';
    $classes = $baseClasses . ' ' . $errorClasses . ' ' . ($class ?? '');
@endphp

<div class="space-y-1">
    {{-- Label --}}
    @if (isset($label))
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if (isset($required) && $required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    {{-- Input Field --}}
    @if ($type === 'textarea')
        <textarea id="{{ $inputId }}" name="{{ $name ?? '' }}" rows="{{ $rows ?? 3 }}" class="{{ $classes }}"
            @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
            @if (isset($required) && $required) required @endif @if (isset($disabled) && $disabled) disabled @endif
            @if (isset($readonly) && $readonly) readonly @endif
            @if (isset($maxlength)) maxlength="{{ $maxlength }}" @endif
            @if (isset($minlength)) minlength="{{ $minlength }}" @endif>{{ old($name ?? '', $value ?? '') }}</textarea>
    @elseif($type === 'select')
        <select id="{{ $inputId }}" name="{{ $name ?? '' }}" class="{{ $classes }}"
            @if (isset($required) && $required) required @endif @if (isset($disabled) && $disabled) disabled @endif>
            @if (isset($placeholder))
                <option value="">{{ $placeholder }}</option>
            @endif
            @if (isset($options))
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}"
                        {{ old($name ?? '', $value ?? '') == $optionValue ? 'selected' : '' }}>
                        {{ $optionLabel }}
                    </option>
                @endforeach
            @endif
        </select>
    @else
        <input type="{{ $type ?? 'text' }}" id="{{ $inputId }}" name="{{ $name ?? '' }}"
            value="{{ old($name ?? '', $value ?? '') }}" class="{{ $classes }}"
            @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
            @if (isset($required) && $required) required @endif @if (isset($disabled) && $disabled) disabled @endif
            @if (isset($readonly) && $readonly) readonly @endif
            @if (isset($maxlength)) maxlength="{{ $maxlength }}" @endif
            @if (isset($minlength)) minlength="{{ $minlength }}" @endif
            @if (isset($min)) min="{{ $min }}" @endif
            @if (isset($max)) max="{{ $max }}" @endif
            @if (isset($step)) step="{{ $step }}" @endif
            @if (isset($pattern)) pattern="{{ $pattern }}" @endif
            @if (isset($autocomplete)) autocomplete="{{ $autocomplete }}" @endif>
    @endif

    {{-- Help Text --}}
    @if (isset($help) && !$hasError)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif

    {{-- Error Message --}}
    @if ($hasError)
        <p class="text-sm text-red-600">{{ $errors->first($name ?? '') }}</p>
    @endif
</div>
