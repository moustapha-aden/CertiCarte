<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Connexion Proviseur - Lycée de Balbala</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-gradient-to-br from-blue-500 via-purple-200 to-indigo-900 min-h-screen flex justify-center items-center p-4">

    <div class="bg-white/95 backdrop-blur-sm p-8 md:p-10 rounded-2xl shadow-2xl w-full max-w-md border border-white/20">

        <!-- En-tête avec logo -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-blue-800 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg transform hover:scale-105 transition-transform duration-300">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z"/>
                    <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2 bg-gradient-to-r from-blue-800 to-purple-700 bg-clip-text text-transparent">Lycée de Balbala</h1>
            <p class="text-gray-600 font-medium text-lg">Espace Proviseur</p>
            <div class="w-20 h-1 bg-gradient-to-r from-blue-800 to-purple-600 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Message de bienvenue -->
        <div class="bg-blue-50 border-l-4 border-blue-800 p-4 rounded-r-lg mb-6 transform hover:scale-105 transition-transform duration-200">
            <p class="text-sm text-blue-800">
                <span class="font-bold">Bienvenue M./Mme le Proviseur</span><br>
                <span class="text-blue-700">Accédez à votre espace de gestion administrative</span>
            </p>
        </div>

        <!-- Formulaire de Connexion -->
        <form method="POST" action="{{ route('authenticate') }}" class="space-y-6">
            @csrf

            <!-- Champ Email -->
            <div class="space-y-2">
                <label for="email" class="block font-semibold text-gray-700 flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2 text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.89 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                    </svg>
                    Adresse Email Professionnelle
                </label>
                <input id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="proviseur@lycee-balbala.dj"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-200 transition-all duration-300 hover:border-blue-400 transform hover:-translate-y-1 focus:-translate-y-1 hover:shadow-lg focus:shadow-lg">

                @error('email')
                    <div class="flex items-center text-red-500 text-sm mt-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        </svg>
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <!-- Champ Mot de Passe -->
            <div class="space-y-2">
                <label for="password" class="block font-semibold text-gray-700 flex items-center text-sm">
                    <svg class="w-4 h-4 mr-2 text-blue-800" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18,8h-1V6c0-2.76-2.24-5-5-5S7,3.24,7,6v2H6c-1.1,0-2,0.9-2,2v10c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V10C20,8.9,19.1,8,18,8z M12,17c-1.1,0-2-0.9-2-2s0.9-2,2-2s2,0.9,2,2S13.1,17,12,17z M15.1,8H8.9V6c0-1.71,1.39-3.1,3.1-3.1s3.1,1.39,3.1,3.1V8z"/>
                    </svg>
                    Mot de Passe Sécurisé
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="••••••••"
                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-800 focus:ring-2 focus:ring-blue-200 transition-all duration-300 hover:border-blue-400 transform hover:-translate-y-1 focus:-translate-y-1 hover:shadow-lg focus:shadow-lg">

                @error('password')
                    <div class="flex items-center text-red-500 text-sm mt-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                        </svg>
                        <strong>{{ $message }}</strong>
                    </div>
                @enderror
            </div>

            <!-- Options -->
            <div class="flex items-center justify-between pt-2">
                <div class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           id="remember"
                           {{ old('remember') ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-800 border-2 border-gray-300 rounded focus:ring-blue-500 focus:ring-2 transition-all">
                    <label for="remember" class="ml-2 text-sm font-medium text-gray-700 select-none cursor-pointer hover:text-blue-800 transition-colors">
                        Rester connecté
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                       class="text-sm text-blue-800 hover:text-blue-600 font-medium hover:underline transition-all duration-200 transform hover:scale-105">
                        Mot de passe oublié?
                    </a>
                @endif
            </div>

            <!-- Bouton de Connexion -->
            <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-blue-800 to-purple-700 hover:from-blue-900 hover:to-purple-800 text-white rounded-lg font-bold text-lg transition-all duration-300 transform hover:-translate-y-1 hover:shadow-2xl active:scale-95 flex items-center justify-center space-x-2 group">
                <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10,17l5-5l-5-5v3H1v4h9V17z M20,2H8C6.9,2,6,2.9,6,4v4h2V4h12v16H8v-4H6v4c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2V4C22,2.9,21.1,2,20,2z"/>
                </svg>
                <span>Accéder au Tableau de Bord</span>
            </button>
        </form>

        <!-- Séparateur -->
        <div class="my-6 flex items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <div class="mx-4 text-gray-500 text-sm font-medium">Informations</div>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="space-y-3 text-center">
            <div class="bg-gray-50 rounded-lg p-3 hover:bg-gray-100 transition-colors cursor-pointer">
                <div class="flex items-center justify-center space-x-2 text-gray-700">
                    <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,16.5L6.5,12L7.91,10.59L11,13.67L16.59,8.09L18,9.5L11,16.5Z"/>
                    </svg>
                    <span class="text-sm font-medium">Connexion sécurisée SSL</span>
                </div>
            </div>

            <div class="flex items-center justify-center space-x-4 text-xs text-gray-500">
                <span>Assistance technique :</span>
                <a href="tel:+253-77-12-34-56" class="text-blue-600 hover:text-blue-800 font-medium hover:underline transition-colors">
                    +253 77 12 34 56
                </a>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center space-y-2">
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide">Système de Gestion Éducative</p>
            <p class="text-xs text-gray-400">© 2025 Lycée de Balbala - République de Djibouti</p>
            <div class="flex justify-center space-x-1 mt-2">
                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                <div class="w-2 h-2 bg-white border border-gray-300 rounded-full"></div>
                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
            </div>
        </div>
    </div>

</body>
</html>
