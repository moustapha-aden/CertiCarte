{{-- Card Component --}}
<div
    class="bg-white {{ $shadow ?? 'shadow-xl' }} {{ $padding ?? 'p-8' }} rounded-2xl border border-gray-100 {{ $class ?? '' }} {{ isset($hover) && $hover ? 'hover:shadow-2xl transition-shadow duration-300' : '' }}">
    {{-- Card Header (optional) --}}
    @if (isset($title) || isset($subtitle) || isset($actions))
        <div class="mb-6 border-b pb-4">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    @if (isset($title))
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center space-x-3">
                            @if (isset($icon))
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    {!! $icon !!}
                                </svg>
                            @endif
                            <span>{{ $title }}</span>
                        </h2>
                    @endif
                    @if (isset($subtitle))
                        <p class="text-gray-600 mt-2">{{ $subtitle }}</p>
                    @endif
                </div>

                {{-- Card Actions (optional) --}}
                @if (isset($actions))
                    <div class="flex items-center space-x-2">
                        {!! $actions !!}
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Card Content --}}
    <div class="{{ isset($title) || isset($subtitle) || isset($actions) ? '' : 'mt-0' }}">
        {{ $slot }}
    </div>

    {{-- Card Footer (optional) --}}
    @if (isset($footer))
        <div class="mt-6 pt-4 border-t border-gray-200">
            {!! $footer !!}
        </div>
    @endif
</div>
