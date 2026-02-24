@extends('layouts.auth')

@section('title', 'Réinitialiser mot de passe - Lycée Ahmed Farah Ali')
@section('description', 'Définissez un nouveau mot de passe')

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
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Nouveau mot de passe</h1>
        <p class="text-gray-600 text-sm">
            Choisissez un mot de passe sécurisé
        </p>
    </div>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
        @csrf

        {{-- Token --}}
        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Adresse Email
            </label>
            <input type="email" name="email" value="{{ old('email', request('email')) }}" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Nouveau mot de passe
            </label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Confirmer mot de passe
            </label>
            <input type="password" name="password_confirmation" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none">
        </div>

        <button type="submit"
            class="w-full py-3 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            Réinitialiser
        </button>
    </form>
</div>
@endsection
