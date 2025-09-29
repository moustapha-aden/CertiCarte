<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Connexion - Lycée de Balbala')</title>
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

<body class="bg-gradient-to-br from-blue-500 via-purple-200 to-indigo-900 min-h-screen">
    {{-- Main Content Area --}}
    <main class="min-h-screen flex items-center justify-center p-4">
        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="fixed top-4 right-4 z-50">
                <x-flash-message type="success" :message="session('success')" />
            </div>
        @endif

        @if (session('error'))
            <div class="fixed top-4 right-4 z-50">
                <x-flash-message type="error" :message="session('error')" />
            </div>
        @endif

        @if (session('warning'))
            <div class="fixed top-4 right-4 z-50">
                <x-flash-message type="warning" :message="session('warning')" />
            </div>
        @endif

        @if (session('info'))
            <div class="fixed top-4 right-4 z-50">
                <x-flash-message type="info" :message="session('info')" />
            </div>
        @endif

        {{-- Page Content --}}
        @yield('content')
    </main>

    {{-- Additional JavaScript --}}
    @stack('scripts')
</body>

</html>
