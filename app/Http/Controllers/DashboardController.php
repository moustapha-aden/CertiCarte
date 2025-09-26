<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Classe;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupère le nombre total d'élèves
        $totalStudents = Student::count();

        // Récupère le nombre total de classes
        $totalClasses = Classe::count();

        // Simule d'autres données pour la carte
        $eleves_inscrits = $totalStudents; // Utilisation de la variable que vous avez dans le code Blade
        $classes_actives = $totalClasses;
        $evolution_eleves = 5.2; // Remplacer par une logique réelle si disponible
        $taux_reussite = 94.2;   // Remplacer par une logique réelle si disponible

        return view('Administrateur.Dashboard', compact('eleves_inscrits', 'classes_actives', 'evolution_eleves', 'taux_reussite'));
    }
}
