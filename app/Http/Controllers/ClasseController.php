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
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        $selectedYearId = $request->get('year_id', $schoolYears->first()?->id);

        $query = Classe::with(['schoolYear', 'students']);

        if ($selectedYearId) {
            $query->where('year_id', $selectedYearId);
        }

        $classes = $query->paginate(12);

        return view('classes.index', compact('classes', 'schoolYears', 'selectedYearId'));
    }

    /**
     * Display students for the specified class.
     *
     * @param Classe $classe
     * @return View
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
     * @return View
     */
    public function create(): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        return view('classes.create', compact('schoolYears'));
    }

    /**
     * Store a newly created class in storage.
     *
     * @param StoreClasseRequest $request
     * @return RedirectResponse
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
     * @param Classe $classe
     * @return View
     */
    public function edit(Classe $classe): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();
        return view('classes.edit', compact('classe', 'schoolYears'));
    }

    /**
     * Update the specified class in storage.
     *
     * @param UpdateClasseRequest $request
     * @param Classe $classe
     * @return RedirectResponse
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
     * @param Classe $classe
     * @return RedirectResponse
     */
    public function destroy(Classe $classe): RedirectResponse
    {
        try {
            if ($classe->students()->count() > 0) {
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
