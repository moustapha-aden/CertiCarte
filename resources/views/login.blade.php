@extends('layouts.auth')

@section('title', 'Connexion - Lycée Ahmed Farah Ali')
@section('description', 'Accédez à votre espace de gestion administrative')

@section('content')
    {{-- Login Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md border border-gray-100">
        {{-- Logo and Title --}}
        <div class="text-center mb-8">
            <div
                class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z" />
                    <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Connexion</h1>
            <p class="text-gray-600">Lycée Ahmed Farah Ali</p>
        </div>

        {{-- Login Form --}}
        <form method="POST" action="{{ route('authenticate') }}" class="space-y-6">
            @csrf

            {{-- Email Field --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse Email
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="email" placeholder="proviseur@lycee-balbala.dj"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password Field --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de Passe
                </label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors pr-10">

                    {{-- Password Toggle Button --}}
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-600">
                        <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember Me & Forgot Password --}}
            <div class="flex items-center justify-between">
                <!-- <div class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           id="remember"
                           {{ old('remember') ? 'checked' : '' }}
                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    <label for="remember" class="ml-2 block text-sm text-gray-700">
                        Rester connecté
                    </label>
                </div> -->

                <!-- @if (Route::has('password.request')) -->
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                        Mot de passe oublié?
                    </a>
                @endif
            </div>

            {{-- Submit Button --}}
            <button type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Se connecter
            </button>
        </form>

        {{-- Footer --}}
        <div class="mt-8 pt-6 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500">© 2025 Lycée Ahmed Farah Ali</p>
        </div>
    </div>

    {{-- Password Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (togglePassword && password && eyeIcon) {
                togglePassword.addEventListener('click', function() {
                    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                    password.setAttribute('type', type);

                    // Toggle eye icon
                    if (type === 'password') {
                        eyeIcon.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
                    } else {
                        eyeIcon.innerHTML =
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.255-3.452m3.518-2.518A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.955 9.955 0 01-1.186 2.044M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>';
                    }
                });
            }
        });
    </script>
@endsection
