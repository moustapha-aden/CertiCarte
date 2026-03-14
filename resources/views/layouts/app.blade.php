<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lycée Ahmed Farah Ali')</title>
    <meta name="description" content="@yield('description', 'Système de gestion scolaire du Lycée Ahmed Farah Ali')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional CSS --}}
    @stack('styles')

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">
</head>

<body class="bg-gray-50 min-h-screen">
    {{-- Header Component --}}
    @include('partials._header')

    {{-- Main Content Area --}}
    <main class="max-w-7xl mx-auto px-4 py-6 sm:px-6 sm:py-8 lg:px-8 lg:pt-10">
        {{-- Breadcrumbs (optional) --}}
        @hasSection('breadcrumbs')
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm">
                    @yield('breadcrumbs')
                </ol>
            </nav>
        @endif

        {{-- Flash Messages --}}
        @if (session('success'))
            <x-flash-message type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-flash-message type="error" :message="session('error')" />
        @endif

        @if (session('warning'))
            <x-flash-message type="warning" :message="session('warning')" />
        @endif

        @if (session('info'))
            <x-flash-message type="info" :message="session('info')" />
        @endif

        {{-- Page Content --}}
        @yield('content')
    </main>

    {{-- Footer (optional) --}}
    @hasSection('footer')
        <footer class="bg-white border-t border-gray-200 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                @yield('footer')
            </div>
        </footer>
    @endif

    {{-- Additional JavaScript --}}
    @stack('scripts')
</body>

</html>
