<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classe; // Ajouté : Pour afficher la liste des classes lors de la création/édition
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Ajouté : Pour gérer l'upload de fichiers

class StudentController extends Controller
{
    /**
     * Affiche la liste des élèves avec pagination.
     */
    public function index()
    {
        // Récupère tous les élèves avec leur classe associée (relation 'class')
        $students = Student::with('class')->paginate(10);

        // Retourne la vue 'students.index' en lui passant la liste des élèves
        return view('students.index', compact('students'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel élève.
     */
    public function create()
    {
        // Récupère la liste de toutes les classes pour le menu déroulant
        $classes = Classe::pluck('name', 'id');

        // Retourne la vue 'students.create'
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
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',
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
        Student::create($validatedData);

        // 4. Redirection avec message de succès
        return redirect()->route('students.index')
                         ->with('success', 'L\'élève a été ajouté avec succès.');
    }

    /**
     * Affiche les détails de l'élève spécifié.
     */
    public function show(Student $student)
    {
        // La variable $student est injectée par l'ID de la route
        // On s'assure que la relation 'class' est chargée
        $student->load('class');

        return view('students.show', compact('student'));
    }

    /**
     * Affiche le formulaire pour modifier l'élève spécifié.
     */
    public function edit(Student $student)
    {
        // Récupère la liste de toutes les classes pour le menu déroulant
        $classes = Classe::pluck('name', 'id');

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
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:M,F',
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
