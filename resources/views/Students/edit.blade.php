<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification Étudiant : {{ $student->name ?? 'Dossier' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- HEADER (En-tête Simple et Unique) --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Titre / Logo --}}
                <div class="flex items-center space-x-4">
                    <div class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala 🎓</div>
                    <p class="text-xs text-gray-500 font-medium hidden sm:block">Modification de Dossier Élève</p>
                </div>

                {{-- Actions / Profil (Simplifié) --}}
                <div class="flex items-center space-x-4">
                    {{-- Bouton Retour --}}
                    <a href="{{ route('students.index') }}"
                       class="px-3 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition duration-200 text-sm">
                        <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Retour Liste
                    </a>

                    {{-- Déconnexion --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
                            Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT (Contenu du Formulaire) --}}
    <main class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-2xl shadow-xl border border-gray-200">

            {{-- Titre du Formulaire --}}
            <h1 class="text-3xl font-extrabold text-gray-800 mb-6 border-b pb-3 flex items-center">
                <svg class="w-7 h-7 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                <span>Modification : {{ $student->name }}</span>
            </h1>

            <form action="{{ route('students.update', $student->id) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nom Complet (name) --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700">Nom Complet</label>
                    <input type="text" name="name" id="name"
                            value="{{ old('name', $student->name) }}" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Date de Naissance (date_of_birth) --}}
                    <div>
                        <label for="date_of_birth" class="block text-sm font-semibold text-gray-700">Date de Naissance</label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                                value="{{ old('date_of_birth', \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d')) }}" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Genre (gender) --}}
                    <div>
                        <label for="gender" class="block text-sm font-semibold text-gray-700">Genre</label>
                        <select name="gender" id="gender" required
                                class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                            <option value="">Sélectionnez le genre</option>
                            <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Masculin (M)</option>
                            <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Féminin (F)</option>
                        </select>
                        @error('gender')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Classe (class_id) --}}
                <div>
                    <label for="class_id" class="block text-sm font-semibold text-gray-700">Classe</label>
                    <select name="class_id" id="class_id" required
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm p-3 focus:border-indigo-500 focus:ring-indigo-500 transition duration-150">
                        <option value="">Sélectionnez une classe</option>

                        {{-- LA CORRECTION CLÉ : Utilisation de la clé ($id) et de la valeur ($label) --}}
                        @foreach ($classes as $id => $label)
                            <option value="{{ $id }}" {{ old('class_id', $student->class_id) == $id ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ pour l'upload de photo (Optionnel) --}}
                <div>
                    <label for="photo" class="block text-sm font-semibold text-gray-700">Mettre à jour la photo (Optionnel)</label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white file:mr-4 file:py-3 file:px-4 file:rounded-r-none file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 focus:outline-none transition duration-150">
                    <p class="text-xs text-gray-500 mt-1">La photo actuelle sera remplacée si un nouveau fichier est sélectionné.</p>
                    @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="pt-4">
                    <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition duration-300 shadow-md flex items-center justify-center space-x-2 transform hover:scale-[1.005]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Enregistrer les Modifications</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500 border-t pt-4">
                <a href="{{ route('classes.students', $student->class_id) }}" class="text-indigo-600 hover:text-indigo-800 transition duration-150">← Annuler et Retourner à la classe de l'élève</a>
            </div>
        </div>
    </main>

</body>
</html>
