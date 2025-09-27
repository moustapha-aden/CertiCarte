<form action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" 
      method="POST" 
      class="bg-white p-6 rounded-2xl shadow-lg space-y-5 max-w-md mx-auto mt-8">

    @csrf
    @if(isset($user))
        @method('PUT')
    @endif

    {{-- Nom --}}
    <div>
        <label for="name" class="block font-semibold mb-1 text-gray-700">Nom :</label>
        <input type="text" name="name" id="name" 
               value="{{ old('name', $user->name ?? '') }}" 
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        @error('name') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Email --}}
    <div>
        <label for="email" class="block font-semibold mb-1 text-gray-700">Email :</label>
        <input type="email" name="email" id="email" 
               value="{{ old('email', $user->email ?? '') }}" 
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        @error('email') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Rôle --}}
    <div>
        <label for="role" class="block font-semibold mb-1 text-gray-700">Rôle :</label>
        <select name="role" id="role" 
                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
            <option value="admin" {{ (old('role', $user->role ?? '') == 'admin') ? 'selected' : '' }}>Admin</option>
            <option value="secretary" {{ (old('role', $user->role ?? '') == 'secretary') ? 'selected' : '' }}>Secrétaire</option>
        </select>
        @error('role') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Mot de passe --}}
    <div>
        <label for="password" class="block font-semibold mb-1 text-gray-700">
            {{ isset($user) ? 'Nouveau mot de passe (laisser vide si inchangé)' : 'Mot de passe' }} :
        </label>
        <input type="password" name="password" id="password" 
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
        @error('password') 
            <span class="text-red-500 text-sm">{{ $message }}</span> 
        @enderror
    </div>

    {{-- Confirmation --}}
    <div>
        <label for="password_confirmation" class="block font-semibold mb-1 text-gray-700">Confirmation :</label>
        <input type="password" name="password_confirmation" id="password_confirmation" 
               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none">
    </div>

    {{-- Bouton --}}
    <button type="submit" 
            class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-xl transition-colors">
        {{ isset($user) ? 'Mettre à jour' : 'Créer' }}
    </button>

</form>
