<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Modification Étudiant : {{ $student->name ?? 'Dossier' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Ajout d'une transition subtile pour le corps */
        body {
            transition: background-color 0.3s ease;
        }

        /* Style pour masquer la fonction 'file' par défaut et utiliser un style plus moderne */
        input[type="file"]::file-selector-button {
            background-color: #e0f2fe; /* blue-50 */
            color: #1d4ed8; /* blue-700 */
            border-right: 1px solid #93c5fd; /* blue-300 */
            padding-right: 1rem;
        }

        /* Coordonne les paddings pour tous les inputs/selects */
        .custom-input-style {
            padding-top: 0.75rem; /* p-3 */
            padding-bottom: 0.75rem; /* p-3 */
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ************************************************************* --}}
    {{-- HEADER (Style Dashboard Harmonisé) --}}
    {{-- ************************************************************* --}}
    <header class="bg-white sticky top-0 z-10 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                {{-- Logo et Titre --}}
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-cyan-500 rounded-lg flex items-center justify-center shadow-lg">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zm0 2.18L18.09 9L12 12.73L5.91 9L12 5.18z"/>
                            <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold text-gray-900 tracking-tight">Lycée de Balbala</h1>
                        <p class="text-xs text-gray-500 font-medium hidden sm:block">Modification de Dossier Élève</p>
                    </div>
                </div>

                {{-- Actions / Profil (Déconnexion standardisée) --}}
                <div class="flex items-center space-x-6">
                    {{-- Bouton Retour à la Liste --}}
                    <a href="{{ route('students.index') ?? '#' }}"
                       class="hidden sm:flex px-3 py-2 bg-indigo-50 text-indigo-700 font-semibold rounded-xl hover:bg-indigo-100 transition duration-200 text-sm items-center space-x-1 border border-indigo-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="hidden md:inline">Retour Liste</span>
                    </a>

                    {{-- Déconnexion (Style standard) --}}
                    <form method="POST" action="{{ route('logout') ?? '#' }}">
                        @csrf
                        <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-700 px-3 py-2 rounded-xl text-sm font-semibold transition-all duration-200 items-center space-x-2 border border-red-200 flex">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 17v-3H9v-4h7V7l5 5-5 5M14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z"/>
                            </svg>
                            <span class="hidden md:inline">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- ************************************************************* --}}
    {{-- MAIN CONTENT (Formulaire Style Dashboard) --}}
    {{-- ************************************************************* --}}
    <main class="flex-grow max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8 w-full">
        <div class="max-w-xl mx-auto bg-white p-8 rounded-3xl shadow-2xl border border-gray-100">

            {{-- Titre de la Section --}}
            <h1 class="text-3xl font-extrabold text-gray-900 mb-8 border-b pb-4 flex items-center space-x-3">
                <svg class="w-7 h-7 text-indigo-600" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                <span>Modification du Dossier</span>
            </h1>

            <form action="{{ route('students.update', $student->id) ?? '#' }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Nom Complet (name) --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom Complet <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name"
                            value="{{ old('name', $student->name) }}" required
                            class="custom-input-style mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg shadow-sm px-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition duration-150">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Groupe de 2 champs --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Date de Naissance (date_of_birth) --}}
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date de Naissance <span class="text-red-500">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth"
                                value="{{ old('date_of_birth', \Carbon\Carbon::parse($student->date_of_birth)->format('Y-m-d')) }}" required
                                class="custom-input-style mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg shadow-sm px-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition duration-150">
                        @error('date_of_birth')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Genre (gender) --}}
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700">Genre <span class="text-red-500">*</span></label>
                        <select name="gender" id="gender" required
                                class="custom-input-style mt-1 block w-full text-gray-900 border border-gray-300 rounded-lg shadow-sm px-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition duration-150">
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
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Classe d'Affectation <span class="text-red-500">*</span></label>
                    <select name="class_id" id="class_id" required
                            class="custom-input-style mt-1 block w-full text-gray-900 border border-gray-300 rounded-lg shadow-sm px-3 bg-white focus:border-indigo-500 focus:ring-indigo-500 focus:outline-none transition duration-150">
                        <option value="">Sélectionnez une classe</option>

                        @if(isset($classes) && is_iterable($classes))
                            @foreach ($classes as $id => $label)
                                <option value="{{ $id }}" {{ old('class_id', $student->class_id) == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>⚠️ Erreur : Aucune classe disponible</option>
                        @endif
                    </select>
                    @error('class_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Champ pour l'upload de photo (Style harmonisé, couleur bleue) --}}
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Mettre à jour la photo (Optionnel)</label>
                    <input type="file" name="photo" id="photo" accept="image/*"
                            class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-3 file:px-4 file:rounded-r-none file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition duration-150">
                    <p class="text-xs text-gray-500 mt-1">La photo actuelle sera remplacée si un nouveau fichier est sélectionné.</p>
                    @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Bouton de Soumission (Bouton d'action Bleu/Indigo) --}}
                <div class="pt-4">
                    <button type="submit" class="w-full px-4 py-3 bg-indigo-600 text-white font-extrabold text-lg rounded-xl hover:bg-indigo-700 transition duration-300 shadow-xl flex items-center justify-center space-x-2 transform hover:scale-[1.01] hover:shadow-2xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>ENREGISTRER LES MODIFICATIONS</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm text-gray-500 border-t pt-4">
                <a href="{{ route('classes.students', $student->class_id) ?? '#' }}" class="text-indigo-600 font-medium hover:text-indigo-800 transition duration-150 flex items-center justify-center space-x-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    <span>Annuler et Retourner à la fiche de l'élève</span>
                </a>
            </div>
        </div>
    </main>

</body>
</html>
