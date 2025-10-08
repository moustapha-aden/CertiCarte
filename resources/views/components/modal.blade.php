{{-- Modal Component --}}
@props([
    'id' => 'modal',
    'title' => '',
    'size' => 'sm',
    'closable' => true,
    'backdrop' => true,
])

@php
    $sizeClasses = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-2xl',
        'full' => 'max-w-full mx-4',
    ];

    $modalSize = $sizeClasses[$size] ?? $sizeClasses['sm'];
@endphp

<div id="{{ $id }}"
    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-0 overflow-y-auto h-full w-full z-50 backdrop-blur-sm transition-all duration-300 p-4 opacity-0"
    style="display: none;" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-title"
    onclick="closeModalOnBackdrop(event)">

    <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden transform transition-all duration-300 w-full {{ $modalSize }} max-h-[90vh] overflow-y-auto scale-95 translate-y-4"
        onclick="event.stopPropagation()">

        {{-- Modal Header --}}
        @if ($title || $closable)
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100">
                <div class="flex items-center justify-between">
                    @if ($title)
                        <h3 id="{{ $id }}-title"
                            class="text-lg font-bold text-gray-900 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                            <span>{{ $title }}</span>
                        </h3>
                    @endif

                    @if ($closable)
                        <button type="button" onclick="closeModal()"
                            class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg p-1.5 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            aria-label="Fermer">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
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
        @if (isset($footer))
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                {{ $footer }}
            </div>
        @endif
    </div>
</div>

<script>
    function openModal() {
        const modal = document.getElementById('{{ $id }}');
        const modalContent = modal.querySelector('div');

        if (modal) {
            // Show modal
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';

            // Trigger animations after a small delay to ensure display change is processed
            setTimeout(() => {
                modal.classList.remove('opacity-0', 'bg-opacity-0');
                modal.classList.add('opacity-100', 'bg-opacity-50');

                modalContent.classList.remove('scale-95', 'translate-y-4');
                modalContent.classList.add('scale-100', 'translate-y-0');
            }, 10);

            // Focus management
            setTimeout(() => {
                const firstFocusable = modal.querySelector(
                    'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
                if (firstFocusable) {
                    firstFocusable.focus();
                }
            }, 300);
        }
    }

    function closeModal() {
        const modal = document.getElementById('{{ $id }}');
        const modalContent = modal.querySelector('div');

        if (modal) {
            // Start closing animation
            modal.classList.remove('opacity-100', 'bg-opacity-50');
            modal.classList.add('opacity-0', 'bg-opacity-0');

            modalContent.classList.remove('scale-100', 'translate-y-0');
            modalContent.classList.add('scale-95', 'translate-y-4');

            // Hide modal after animation completes
            setTimeout(() => {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }, 300);
        }
    }

    function closeModalOnBackdrop(event) {
        // Only close if clicking on the backdrop (not on the modal content)
        if (event.target === event.currentTarget) {
            closeModal();
        }
    }

    // Global modal functions
    window.openModal = openModal;
    window.closeModal = closeModal;

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('{{ $id }}');
            if (modal && modal.style.display !== 'none') {
                closeModal();
            }
        }
    });
</script>
