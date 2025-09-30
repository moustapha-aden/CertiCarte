<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Lycée de Balbala')</title>
    <meta name="description" content="@yield('description', 'Système de gestion scolaire du Lycée de Balbala')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Additional CSS --}}
    @stack('styles')

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
</head>

<body class="bg-gray-50 min-h-screen">
    {{-- Header Component --}}
    @include('partials.header')

    {{-- Main Content Area --}}
    <main class="max-w-7xl mx-auto p-8 pt-10 sm:px-6 lg:px-8">
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
