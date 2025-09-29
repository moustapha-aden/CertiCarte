<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Models\Classe;
use App\Models\SchoolYear;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Controller for managing classes (Classe CRUD operations)
 */
class ClasseController extends Controller
{
    /**
     * Display a listing of classes with optional year filter.
     *
     * @param  Request  $request  The HTTP request containing optional year_id filter parameter
     * @return View The classes index view with paginated classes filtered by school year
     */
    public function index(Request $request): View
    {
        // Get all school years for the filter dropdown
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();

        // Build the classes query
        $query = Classe::with(['schoolYear'])
            ->withCount('students');

        // Filter by school year
        if ($request->filled('year_id')) {
            $query->where('year_id', $request->input('year_id'));
        }

        $classes = $query->paginate(12)->withQueryString();

        return view('classes.index', [
            'classes' => $classes,
            'schoolYears' => $schoolYears,
        ]);
    }

    /**
     * Display the specified class with its students.
     *
     * @param  Classe  $classe  The class to display
     * @return View The class details view with students list
     */
    public function show(Classe $classe): View
    {
        $students = $classe->students()->with('classe')->paginate(10);

        return view('classes.show', compact('classe', 'students'));
    }

    /**
     * Show the form for creating a new class.
     *
     * @return View The class creation form view
     */
    public function create(): View
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class in storage.
     *
     * @param  StoreClasseRequest  $request  The validated request containing class data
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function store(StoreClasseRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Handle school year: create or find existing
            $schoolYear = SchoolYear::firstOrCreate(
                ['year' => $validatedData['year']],
                ['year' => $validatedData['year']]
            );

            // Create the class with the school year ID
            $classe = Classe::create([
                'label' => $validatedData['label'],
                'year_id' => $schoolYear->id,
            ]);

            Log::info('Nouvelle classe créée: '.$classe->label.' (ID: '.$classe->id.') pour l\'année '.$schoolYear->year);

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classe->label.'" a été créée avec succès pour l\'année '.$schoolYear->year.'.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la classe: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Show the form for editing the specified class.
     *
     * @param  Classe  $classe  The class to edit
     * @return View The class edit form view
     */
    public function edit(Classe $classe): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        return view('classes.edit', compact('classe', 'schoolYears'));
    }

    /**
     * Update the specified class in storage.
     *
     * @param  UpdateClasseRequest  $request  The validated request containing updated class data
     * @param  Classe  $classe  The class to update
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function update(UpdateClasseRequest $request, Classe $classe): RedirectResponse
    {
        try {
            $classe->update($request->validated());
            Log::info('Classe modifiée: '.$classe->label.' (ID: '.$classe->id.')');

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classe->label.'" a été mise à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la classe: '.$e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified class from storage.
     *
     * @param  Classe  $classe  The class to delete
     * @return RedirectResponse Redirect to classes index with success/error message
     */
    public function destroy(Classe $classe): RedirectResponse
    {
        try {
            // Load the students count efficiently
            $classe->loadCount('students');

            if ($classe->students_count > 0) {
                return redirect()->route('classes.index')
                    ->with('error', 'Impossible de supprimer la classe "'.$classe->label.'" car elle contient des étudiants. Veuillez d\'abord réassigner ou supprimer les étudiants.');
            }

            $classLabel = $classe->label;
            $classId = $classe->id;

            $classe->delete();
            Log::info('Classe supprimée: '.$classLabel.' (ID: '.$classId.')');

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classLabel.'" a été supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de la classe: '.$e->getMessage());

            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de la classe. Veuillez réessayer.');
        }
    }
}
