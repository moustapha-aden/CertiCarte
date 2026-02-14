@extends('layouts.auth')

@section('title', 'Mot de passe oublié - Lycée Ahmed Farah Ali')
@section('description', 'Réinitialisez votre mot de passe')

@section('content')
<div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md border border-gray-100">

    {{-- Logo and Title --}}
    <div class="text-center mb-8">
        <div
            class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center shadow-lg">
            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 3L1 9l11 6 11-6-11-6z"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Mot de passe oublié</h1>
        <p class="text-gray-600 text-sm">
            Entrez votre email pour recevoir un lien de réinitialisation
        </p>
    </div>

    {{-- Success Message --}}
    @if (session('status'))
        <div class="mb-4 text-sm text-green-600 text-center">
            {{ session('status') }}
        </div>
    @endif

    {{-- Form --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Adresse Email
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">

            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full py-3 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            Envoyer le lien
        </button>
    </form>

    <div class="mt-6 text-center">
        <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
            Retour à la connexion
        </a>
    </div>
</div>
@endsection
