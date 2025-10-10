<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Models\Classe;
use App\Models\SchoolYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClasseController extends Controller
{
    /**
     * Display a paginated listing of classes with filtering and sorting capabilities.
     *
     * Supports filtering by school year and sorting by label, creation date, or student count.
     * Results are paginated with query string preservation for navigation.
     * Includes student count for each class.
     *
     * @param  Request  $request  The HTTP request containing filter and sort parameters
     * @return View The classes index view with paginated and filtered class data
     *
     * @throws \Exception If database query fails
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

        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort parameters
        $allowedSortFields = ['label', 'created_at', 'students_count'];
        $allowedSortOrders = ['asc', 'desc'];

        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        if (! in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        // Apply sorting
        if ($sortBy === 'students_count') {
            $query->orderBy('students_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $classes = $query->paginate(12)->withQueryString();

        return view('classes.index', [
            'classes' => $classes,
            'schoolYears' => $schoolYears,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Display the specified class with its associated students.
     *
     * Shows detailed class information along with a paginated list of students
     * enrolled in the class. Includes class metadata and student relationships.
     *
     * @param  Classe  $classe  The class model instance to display
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
     * Displays the class creation form with necessary fields
     * for class label and school year selection.
     *
     * @return View The class creation form view
     */
    public function create(): View
    {
        return view('classes.create');
    }

    /**
     * Store a newly created class in the database.
     *
     * Validates incoming request data and creates a new class record.
     * Automatically creates or finds the associated school year.
     * Logs successful creation and handles exceptions gracefully.
     *
     * @param  StoreClasseRequest  $request  The validated request containing class data
     * @return RedirectResponse Redirect to classes index with success/error message
     *
     * @throws \Exception If class creation fails
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

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classe->label.'" a été créée avec succès pour l\'année '.$schoolYear->year.'.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Show the form for editing the specified class.
     *
     * Loads all available school years for dropdown selection
     * and pre-populates the form with current class data.
     *
     * @param  Classe  $classe  The class model instance to edit
     * @return View The class edit form view with pre-populated data
     */
    public function edit(Classe $classe): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        return view('classes.edit', compact('classe', 'schoolYears'));
    }

    /**
     * Update the specified class in the database.
     *
     * Validates incoming request data and updates the class record.
     * Logs successful updates and handles exceptions gracefully.
     *
     * @param  UpdateClasseRequest  $request  The validated request containing updated class data
     * @param  Classe  $classe  The class model instance to update
     * @return RedirectResponse Redirect to classes index with success/error message
     *
     * @throws \Exception If class update fails
     */
    public function update(UpdateClasseRequest $request, Classe $classe): RedirectResponse
    {
        try {
            $classe->update($request->validated());

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classe->label.'" a été mise à jour avec succès.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de la classe. Veuillez réessayer.');
        }
    }

    /**
     * Generate attendance list for the specified class and return as PDF.
     *
     * Creates a printable attendance list using DomPDF library.
     * Supports both single-day and two-day formats based on the 'days' parameter.
     * Includes student names, dates, and class information.
     * Returns the PDF as a stream for inline browser display.
     *
     * @param  Request  $request  The HTTP request containing the 'days' parameter (1 or 2)
     * @param  Classe  $classe  The class model instance to generate list for
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse PDF stream or redirect with error
     *
     * @throws \Exception If PDF generation fails
     */
    public function generateAttendanceList(Request $request, Classe $classe)
    {
        try {
            // Get the 'days' parameter (default to 1)
            // User must pass 'days=1' or 'days=2' via URL
            $days = $request->query('days', 1);

            // Get students from the class, sorted by name
            $students = $classe->students()->orderBy('name')->get();

            // Calculate necessary dates
            $dates = [];
            $today = Carbon::now();
            $dates[] = $today->format('d/m/Y');


                // View for 1 day (portrait format)
                $pdf = Pdf::loadView('classes.attendance_list_print', [
                    'classe' => $classe,
                    'students' => $students,
                    'dates' => $dates,
                    'days' => $days,
                ]);


            // Define filename
            $fileName = 'Liste_Appel_'.$classe->label.'_'.Carbon::now()->format('Ymd').'.pdf';

            // Return PDF in 'stream' mode (direct display in browser)
            return $pdf->stream($fileName);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la génération du PDF. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified class from the database.
     *
     * Permanently deletes the class record after checking for associated students.
     * Prevents deletion if students are still enrolled in the class.
     * This action cannot be undone. Logs successful deletion.
     *
     * @param  Classe  $classe  The class model instance to delete
     * @return RedirectResponse Redirect to classes index with success/error message
     *
     * @throws \Exception If class deletion fails
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
            $classeId = $classe->id;

            $classe->delete();

            return redirect()->route('classes.index')
                ->with('success', 'La classe "'.$classLabel.'" a été supprimée avec succès.');
        } catch (Exception $e) {
            return redirect()->route('classes.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de la classe. Veuillez réessayer.');
        }
    }
}
