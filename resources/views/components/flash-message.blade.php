{{-- Auto-dismissing Flash Message Component --}}
@props(['type' => 'info', 'message' => '', 'duration' => 3000])

@php
    $config = [
        'success' => [
            'bg' => 'bg-green-100',
            'border' => 'border-green-500',
            'text' => 'text-green-700',
            'icon' =>
                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>',
        ],
        'error' => [
            'bg' => 'bg-red-100',
            'border' => 'border-red-500',
            'text' => 'text-red-700',
            'icon' =>
                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>',
        ],
        'warning' => [
            'bg' => 'bg-yellow-100',
            'border' => 'border-yellow-500',
            'text' => 'text-yellow-700',
            'icon' =>
                '<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>',
        ],
        'info' => [
            'bg' => 'bg-blue-100',
            'border' => 'border-blue-500',
            'text' => 'text-blue-700',
            'icon' =>
                '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>',
        ],
    ];

    $style = $config[$type] ?? $config['info'];
@endphp

<div x-data="{
    show: true,
    dismiss() {
        this.show = false;
    }
}" x-init="setTimeout(() => {
    dismiss();
}, {{ $duration }});" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
    class="{{ $style['bg'] }} border-l-4 {{ $style['border'] }} {{ $style['text'] }} p-4 mb-6 rounded-lg shadow-sm"
    role="alert">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                {!! $style['icon'] !!}
            </svg>
            <span class="font-medium">{{ $message }}</span>
        </div>

        {{-- Manual dismiss button --}}
        <button @click="dismiss()"
            class="ml-4 flex-shrink-0 {{ $style['text'] }} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-current rounded-md p-1"
            aria-label="Fermer">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
</div>
