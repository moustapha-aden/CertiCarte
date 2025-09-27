<?php

namespace App\Http\Controllers;

use App\Models\Classe; // Ajouté : Pour afficher la liste des classes lors de la création/édition
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage; // Ajouté : Pour gérer l'upload de fichiers

class StudentController extends Controller
{
    /**
     * Affiche la liste des élèves avec pagination.
     */
    public function index(Request $request)
    {
        // --- 1. Préparation des Listes de Filtres ---

        // A. Récupérer toutes les années scolaires disponibles (pour le premier sélecteur)
        // On suppose que le modèle SchoolYear a une colonne 'start_year' ou 'year' pour trier/afficher
        $allYears = SchoolYear::select('id', 'year')
                              ->orderBy('year', 'desc')
                              ->pluck('year', 'id'); // Pluck(value, key)

        // B. Déterminer l'ID de l'année actuellement sélectionnée
        $selectedYearId = $request->input('year');
        $classesByYear = collect(); // Initialiser une collection vide pour les classes

        // C. Si une année est sélectionnée, récupérer les classes associées
        if ($selectedYearId) {
            $classesByYear = Classe::where('year_id', $selectedYearId)
                                    ->pluck('label', 'id');
        }

        // --- 2. Construction de la Requête des Étudiants ---

        // Initialisation de la requête avec la relation 'classe' chargée
        $students = Student::with('classe');

        // FILTRE 1 : Année Scolaire (Prioritaire)
        if ($selectedYearId) {
            // Récupérer les IDs des classes valides pour l'année sélectionnée
            $validClassIds = Classe::where('year_id', $selectedYearId)->pluck('id');
            // Appliquer le filtre aux étudiants
            $students->whereIn('class_id', $validClassIds);
        }

        // FILTRE 2 : Classe (Appliqué si une année est sélectionnée et qu'une classe est choisie)
        if ($request->filled('class_id')) {
            $students->where('class_id', $request->input('class_id'));
        }

        // FILTRE 3 : Recherche (Nom ou Matricule)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            // Utiliser une closure pour grouper les conditions OR et ne pas polluer les WHERE précédents
            $students->where(function ($query) use ($searchTerm) {
                 $query->where('name', 'like', $searchTerm)
                       ->orWhere('matricule', 'like', $searchTerm);
            });
        }

        // Note: Le bloc `if (isset($currentClasse))` est inutile ici, car il est géré par la route spécifique
        // si elle existe (e.g., classes/{id}/students). On le retire pour simplifier.

        // --- 3. Exécution et Rendu de la Vue ---

        // Paginer les résultats (maintenir les paramètres de la requête dans les liens)
        $students = $students->paginate(10)->withQueryString();

        return view('students.index', [
            'students' => $students,
            'allYears' => $allYears,           // Pour le sélecteur d'année (ID => Année/Label)
            'classesByYear' => $classesByYear, // Pour le sélecteur de classe (ID => Label)
        ]);
    }
    /**
     * Affiche le formulaire de création d'un nouvel élève.
     */
    public function create()
    {
        // Déterminer l'année scolaire actuelle
        $currentYearStr = date('Y') . '-' . (date('Y') + 1);
        Log::info('Current school year: ' . $currentYearStr);

        // Récupérer l'année scolaire correspondante
        $currentYear = SchoolYear::where('year', $currentYearStr)->first();

        Log::info('Current school year object: ' . $currentYear);
                // Récupérer les classes liées à cette année
        if ($currentYear) {
            $classes = Classe::where('year_id', $currentYear->id)->get();
            Log::info('classes: ' . $classes);
        } else {
            $classes = collect(); // collection vide si l'année n'existe pas encore
        }

        // 2. Passer les classes à la vue
        return view('students.create', compact('classes'));
    }

    /**
     * Enregistre un nouvel élève dans la base de données.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:10|unique:students',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'class_id' => 'required|exists:classes,id',
            // La photo est optionnelle, mais doit être une image si présente
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $photoPath = null;

        // 2. Traitement de l'upload de la photo
        if ($request->hasFile('photo')) {
            // Stocke la photo dans le dossier 'public/photos/students'
            $photoPath = $request->file('photo')->store('photos/students', 'public');
            $validatedData['photo'] = $photoPath;
        }

        // 3. Création de l'enregistrement
        $student = Student::create($validatedData);

        // 4. Redirection avec message de succès
        return redirect()->route('classes.students', $student->class_id)
                         ->with('success', 'L\'élève a été ajouté avec succès.');
    }

    /**
     * Affiche les détails de l'élève spécifié.
     */
    public function show(Student $student)
    {
        // La variable $student est injectée par l'ID de la route
        // On s'assure que la relation 'class' est chargée
        $student->load('classe');

        return view('students.show', compact('student'));
    }

    /**
     * Affiche le formulaire pour modifier l'élève spécifié.
     */
    public function edit(Student $student)
    {
        // Récupère la liste de toutes les classes pour le menu déroulant
        $classes = Classe::pluck('label', 'id');

        // Retourne la vue 'students.edit'
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Met à jour l'élève spécifié dans la base de données.
     */
    public function update(Request $request, Student $student)
    {
        // 1. Validation des données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:10|unique:students,matricule,' . $student->id,
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female',
            'class_id' => 'required|exists:classes,id',
            // La photo peut être mise à jour ou non (règle 'sometimes')
            'photo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // 2. Traitement de la nouvelle photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }
            // Stocker la nouvelle photo
            $photoPath = $request->file('photo')->store('photos/students', 'public');
            $validatedData['photo'] = $photoPath;
        }

        // 3. Mise à jour de l'enregistrement
        $student->update($validatedData);

        // 4. Redirection avec message de succès
        return redirect()->route('students.index')
                         ->with('success', 'La fiche élève a été mise à jour.');
    }

    /**
     * Supprime l'élève spécifié de la base de données.
     */
    public function destroy(Student $student)
    {
        // 1. Suppression de la photo associée
        if ($student->photo) {
            Storage::disk('public')->delete($student->photo);
        }

        // 2. Suppression de l'enregistrement
        $student->delete();

        // 3. Redirection avec message de succès
        return redirect()->route('students.index')
                         ->with('success', 'L\'élève a été supprimé avec succès.');
    }
}
