<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ClasseController extends Controller
{
    public function index(Request $request)
    {
        // Récupère toutes les années scolaires pour le filtre
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();

        // Récupère l'année scolaire sélectionnée (par défaut la plus récente)
        $selectedYearId = $request->get('year_id', $schoolYears->first()?->id);

        // Construit la requête pour les classes
        $query = Classe::with(['schoolYear', 'students']);

        // Applique le filtre par année scolaire si spécifié
        if ($selectedYearId) {
            $query->where('year_id', $selectedYearId);
        }

        // Récupère les classes avec pagination
        $classes = $query->paginate(12);

        // Retourne la vue 'classes.index' avec les données
        return view('classes.index', compact('classes', 'schoolYears', 'selectedYearId'));
    }

    /**
     * Affiche la liste des élèves pour la classe spécifiée.
     */
    public function show(Classe $classe)
    {
        // Charge les élèves associés à cette classe avec pagination
        // La relation 'students' est déjà définie dans votre modèle Classe.
        $students = $classe->students()->with('classe')->paginate(10);

        // Retourne la vue 'students.index' en lui passant la classe et la liste filtrée
        return view('students.index', [
            'students' => $students,
            'currentClasse' => $classe // On passe l'objet classe pour l'affichage dans le header
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classe $classe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Classe $classe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classe $classe)
    {
        //
    }
}
