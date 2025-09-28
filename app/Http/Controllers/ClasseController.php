<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Controller for managing classes (Classe CRUD operations)
 *
 * @package App\Http\Controllers
 */
class ClasseController extends Controller
{
    /**
     * Display a listing of classes with optional year filter.
     *
     * @param Request $request The HTTP request containing optional year_id filter parameter
     * @return View The classes index view with paginated classes filtered by school year
     */
    public function index(Request $request): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        $selectedYearId = $request->get('year_id', $schoolYears->first()?->id);

        $query = Classe::with(['schoolYear'])
            ->withCount('students');

        if ($selectedYearId) {
            $query->where('year_id', $selectedYearId);
        }

        $classes = $query->paginate(12);

        return view('classes.index', compact('classes', 'schoolYears', 'selectedYearId'));
    }

    /**
     * Display students for the specified class.
     *
     * @param Classe $classe The class to display students for
     * @return View The students index view filtered by class
     */
    public function show(Classe $classe): View
    {
        $students = $classe->students()->with('classe')->paginate(10);

        return view('students.index', [
            'students' => $students,
            'currentClasse' => $classe
        ]);
    }

    /**
     * Show the form for creating a new class.
     *
     * @return View The class creation form view
     */
    public function create(): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        return view('classes.create', compact('schoolYears'));
    }

    /**
     * Store a newly created class in storage.
     *
     * @param StoreClasseRequest $request The validated request containing class data
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function store(StoreClasseRequest $request): RedirectResponse
    {
        try {
            $classe = Classe::create($request->validated());
            Log::info('Nouvelle classe créée: ' . $classe->label . ' (ID: ' . $classe->id . ')');

            return redirect()->route('classes.index')
                ->with('success', 'La classe "' . $classe->label . '" a été créée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la classe: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Show the form for editing the specified class.
     *
     * @param Classe $classe The class to edit
     * @return View The class edit form view
     */
    public function edit(Classe $classe): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        return view('classes.edit', compact('classe', 'schoolYears'));
    }

    /**
     * Update the specified class in storage.
     *
     * @param UpdateClasseRequest $request The validated request containing updated class data
     * @param Classe $classe The class to update
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function update(UpdateClasseRequest $request, Classe $classe): RedirectResponse
    {
        try {
            $classe->update($request->validated());
            Log::info('Classe modifiée: ' . $classe->label . ' (ID: ' . $classe->id . ')');

            return redirect()->route('classes.index')
                ->with('success', 'La classe "' . $classe->label . '" a été mise à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la classe: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified class from storage.
     *
     * @param Classe $classe The class to delete
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function destroy(Classe $classe): RedirectResponse
    {
        try {
            // Load the students count efficiently
            $classe->loadCount('students');

            if ($classe->students_count > 0) {
                return redirect()->route('classes.index')
                    ->with('error', 'Impossible de supprimer la classe "' . $classe->label . '" car elle contient des étudiants. Veuillez d\'abord réassigner ou supprimer les étudiants.');
            }

            $classLabel = $classe->label;
            $classId = $classe->id;

            $classe->delete();
            Log::info('Classe supprimée: ' . $classLabel . ' (ID: ' . $classId . ')');

            return redirect()->route('classes.index')
                ->with('success', 'La classe "' . $classLabel . '" a été supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la classe: ' . $e->getMessage());
            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de la classe. Veuillez réessayer.');
        }
    }
}
