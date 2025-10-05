{{-- Modal Component --}}
@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'md', // sm, md, lg, xl, full
    'closable' => true,
    'backdrop' => true,
])

@php
    $sizeClasses = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'full' => 'max-w-full mx-4',
    ];
    
    $modalSize = $sizeClasses[$size] ?? $sizeClasses['md'];
@endphp

<div id="{{ $id }}" 
     class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-opacity duration-300"
     x-data="{ show: false }"
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click.self="{{ $closable ? 'closeModal()' : '' }}"
     @keydown.escape.window="{{ $closable ? 'closeModal()' : '' }}"
     role="dialog"
     aria-modal="true"
     aria-labelledby="{{ $id }}-title">
    
    <div class="relative top-20 mx-auto p-5 {{ $modalSize }} w-full">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95">
            
            {{-- Modal Header --}}
            @if($title || $closable)
                <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                    <div class="flex items-center justify-between">
                        @if($title)
                            <h3 id="{{ $id }}-title" class="text-xl font-bold text-gray-900 flex items-center space-x-3">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span>{{ $title }}</span>
                            </h3>
                        @endif
                        
                        @if($closable)
                            <button type="button" 
                                    onclick="closeModal()"
                                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-2 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                    aria-label="Fermer">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Modal Body --}}
            <div class="px-6 py-6">
                {{ $slot }}
            </div>
            
            {{-- Modal Footer (optional) --}}
            @if(isset($footer))
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    {{ $footer }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function openModal() {
        const modal = document.getElementById('{{ $id }}');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus management
        const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        if (firstFocusable) {
            firstFocusable.focus();
        }
    }
    
    function closeModal() {
        const modal = document.getElementById('{{ $id }}');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
    
    // Global modal functions
    window.openModal = openModal;
    window.closeModal = closeModal;
</script>
