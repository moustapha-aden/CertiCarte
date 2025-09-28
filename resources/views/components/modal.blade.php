{{-- Modal Component --}}
<div id="{{ $id ?? 'modal' }}" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
    role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
            onclick="closeModal('{{ $id ?? 'modal' }}')"></div>

        {{-- Modal panel --}}
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            {{-- Modal Header --}}
            @if (isset($title) || isset($header))
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        @if (isset($icon))
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    {!! $icon !!}
                                </svg>
                            </div>
                        @endif
                        <div class="mt-3 text-center sm:mt-0 {{ isset($icon) ? 'sm:ml-4' : '' }} sm:text-left flex-1">
                            @if (isset($title))
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    {{ $title }}
                                </h3>
                            @endif
                            @if (isset($header))
                                {!! $header !!}
                            @endif
                        </div>
                        @if (isset($closable) && $closable !== false)
                            <button type="button"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                onclick="closeModal('{{ $id ?? 'modal' }}')">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Modal Body --}}
            <div class="bg-white px-4 py-5 sm:p-6">
                {{ $slot }}
            </div>

            {{-- Modal Footer --}}
            @if (isset($footer))
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {!! $footer !!}
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal JavaScript --}}
<script>
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modals = document.querySelectorAll('[id^="modal"]');
            modals.forEach(modal => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal.id);
                }
            });
        }
    });
</script>
