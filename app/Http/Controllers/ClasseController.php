<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClasseRequest;
use App\Http\Requests\UpdateClasseRequest;
use App\Models\Classe;
use App\Models\SchoolYear;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
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
     * @return View|JsonResponse The classes index view or JSON with table HTML for AJAX
     *
     * @throws \Exception If database query fails
     */
    public function index(Request $request): View|JsonResponse
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

        // Filter by class name (search)
        if ($request->filled('search')) {
            $searchTerm = '%'.$request->input('search').'%';
            $query->where('label', 'like', $searchTerm);
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

        $data = [
            'classes' => $classes,
            'schoolYears' => $schoolYears,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ];

        if ($request->ajax()) {
            return response()->json([
                'html' => view('classes.partials.table', $data)->render(),
            ]);
        }

        return view('classes.index', $data);
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
    public function show(Request $request, Classe $classe): View
    {
        // Build the students query for this specific class
        $students = $classe->students()->with('classe');

        // Search by name or matricule within this class
        if ($request->filled('search')) {
            $searchTerm = '%'.$request->input('search').'%';
            $students->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('matricule', 'like', $searchTerm);
            });
        }

        $students = $students->paginate(10)->withQueryString();

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
     * @param  Request  $request  The HTTP request
     * @param  Classe  $classe  The class model instance to generate list for
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse PDF stream or redirect with error
     */
    public function generateAttendanceList(Request $request, Classe $classe)
    {
        try {
            $students = $classe->students()->orderBy('name')->get();

            $dates = [];
            $today = Carbon::now();
            $dates[] = $today->format('d/m/Y');

            $pdf = Pdf::loadView('classes.attendance_list_print', [
                'classe' => $classe,
                'students' => $students,
                'dates' => $dates,
            ]);

            $fileName = 'Liste_Appel_'.$classe->label.'_'.Carbon::now()->format('Ymd').'.pdf';

            return $pdf->stream($fileName);
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la génération du PDF. Veuillez réessayer.');
        }
    }


    //generation d'une liste des carte d'identité des étudiants d'une classe
    public function generateIdentityCards(Request $request, Classe $classe)
    {
        try {
            $students = $classe->students()->orderBy('name')->get();

            // Logo en base64
            $logoUrl = null;
            $logoPath = public_path('images/photo_carte.jpg');

            if (file_exists($logoPath)) {
                $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                $data = file_get_contents($logoPath);
                $logoUrl = 'data:image/'.$type.';base64,'.base64_encode($data);
                if(!$logoUrl) {
                    Log::info('Logo vide');
                } else {
                    Log::info('Logo chargé, longueur: '.strlen($logoUrl));
                }
            } else {
                Log::info('Fichier logo non trouvé: '.$logoPath);
            }

            // Background carte en base64
            $backgroundImage = null;
            $bgPath = public_path('images/photo_carte.jpg');

            if (file_exists($bgPath)) {
                $type = pathinfo($bgPath, PATHINFO_EXTENSION);
                $data = file_get_contents($bgPath);
                $backgroundImage = 'data:image/'.$type.';base64,'.base64_encode($data);
            }

            $pdf = Pdf::loadView('classes.identity_cards_print', [
                'classe' => $classe,
                'students' => $students,
                'logoUrl' => $logoUrl,
                'backgroundImage' => $backgroundImage,
            ]);

            return $pdf->stream('Cartes_'.$classe->label.'.pdf');

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erreur génération PDF.');
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
