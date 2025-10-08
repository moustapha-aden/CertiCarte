<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Imports\StudentsImport;
use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;

class StudentController extends Controller
{
    /**
     * Display a paginated listing of students with filtering and sorting capabilities.
     *
     * Supports filtering by class, gender, and search terms (name or matricule).
     * Provides sorting by name, matricule, date of birth, gender, or creation date.
     * Results are paginated with query string preservation for navigation.
     *
     * @param  Request  $request  The HTTP request containing filter and sort parameters
     * @return View The students index view with paginated and filtered student data
     *
     * @throws \Exception If database query fails
     */
    public function index(Request $request): View
    {
        // Get all classes for the filter dropdown
        $allClasses = Classe::select('id', 'label')
            ->orderBy('label')
            ->pluck('label', 'id');

        // Build the students query
        $students = Student::with('classe');

        // Filter by specific class
        if ($request->filled('classe_id')) {
            $students->where('classe_id', $request->input('classe_id'));
        }

        // Search by name or matricule
        if ($request->filled('search')) {
            $searchTerm = '%'.$request->input('search').'%';
            $students->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('matricule', 'like', $searchTerm);
            });
        }

        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort parameters
        $allowedSortFields = ['name', 'matricule', 'date_of_birth', 'gender', 'created_at'];
        $allowedSortOrders = ['asc', 'desc'];

        if (! in_array($sortBy, $allowedSortFields)) {
            $sortBy = 'created_at';
        }

        if (! in_array($sortOrder, $allowedSortOrders)) {
            $sortOrder = 'desc';
        }

        // Apply sorting
        $students->orderBy($sortBy, $sortOrder);

        $students = $students->paginate(10)->withQueryString();

        return view('students.index', [
            'students' => $students,
            'allClasses' => $allClasses,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Show the form for creating a new student.
     *
     * Loads all available school years and classes for dropdown selection.
     * Classes are dynamically loaded based on selected school year.
     *
     * @param  Request  $request  The HTTP request containing school year selection
     * @return View The student creation form view with school years and classes data
     */
    public function create(Request $request): View
    {
        // Get all school years for the dropdown
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        // Get classes based on selected school year
        $selectedYearId = $request->input('school_year_id') ?? old('school_year_id');
        $classes = collect();

        if ($selectedYearId) {
            $classes = Classe::where('year_id', $selectedYearId)->pluck('label', 'id');
        }

        return view('students.create', compact('classes', 'schoolYears'));
    }

    /**
     * Store a newly created student in the database.
     *
     * Validates incoming request data and creates a new student record.
     * Handles optional photo upload to public storage.
     * Logs successful creation and handles exceptions gracefully.
     *
     * @param  StoreStudentRequest  $request  The validated request containing student data
     * @return RedirectResponse Redirect to students index with success/error message
     *
     * @throws \Exception If student creation fails
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('photos/students', 'public');
                $validatedData['photo'] = $photoPath;
            }

            $student = Student::create($validatedData);

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "'.$student->name.'" a été ajouté avec succès.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'étudiant. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified student's detailed information.
     *
     * Loads the student's associated class information and displays
     * all student details including personal information and academic data.
     *
     * @param  Student  $student  The student model instance to display
     * @return View The student details view with complete student information
     */
    public function show(Student $student): View
    {
        $student->load('classe');

        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * Loads all available school years and classes for dropdown selection.
     * Pre-selects the student's current school year and class.
     * Classes are dynamically loaded based on selected school year.
     *
     * @param  Student  $student  The student model instance to edit
     * @param  Request  $request  The HTTP request containing school year selection
     * @return View The student edit form view with pre-populated data
     */
    public function edit(Student $student, Request $request): View
    {
        // Get all school years for the dropdown
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        // Figure out the school year ID:
        $selectedYearId = $request->input('school_year_id')
            ?? old('school_year_id')
            ?? optional($student->classe)->year_id;

        // Get classes for that school year
        $classes = collect();
        if ($selectedYearId) {
            $classes = Classe::where('year_id', $selectedYearId)
                ->pluck('label', 'id');
        }

        return view('students.edit', compact('student', 'classes', 'schoolYears', 'selectedYearId'));
    }

    /**
     * Update the specified student in the database.
     *
     * Validates incoming request data and updates the student record.
     * Handles optional photo update with automatic deletion of old photo.
     * Logs successful updates and handles exceptions gracefully.
     *
     * @param  UpdateStudentRequest  $request  The validated request containing updated student data
     * @param  Student  $student  The student model instance to update
     * @return RedirectResponse Redirect to students index with success/error message
     *
     * @throws \Exception If student update fails
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            // Handle photo update
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
                // Store new photo
                $photoPath = $request->file('photo')->store('photos/students', 'public');
                $validatedData['photo'] = $photoPath;
            }

            $student->update($validatedData);

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "'.$student->name.'" a été mis à jour avec succès.');
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'étudiant. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified student from the database.
     *
     * Permanently deletes the student record and associated photo file.
     * This action cannot be undone. Logs successful deletion.
     *
     * @param  Student  $student  The student model instance to delete
     * @return RedirectResponse Redirect to students index with success/error message
     *
     * @throws \Exception If student deletion fails
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {
            // Delete associated photo if exists
            if ($student->photo) {
                Storage::disk('public')->delete($student->photo);
            }

            $studentName = $student->name;
            $studentId = $student->id;
            $student->delete();

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "'.$studentName.'" a été supprimé avec succès.');
        } catch (Exception $e) {
            return redirect()->route('students.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'étudiant. Veuillez réessayer.');
        }
    }

    /**
     * Get classes by school year for AJAX requests.
     *
     * Returns a JSON response containing classes associated with the specified school year.
     * Used for dynamic dropdown population in student forms.
     * Includes error handling with appropriate HTTP status codes.
     *
     * @param  int  $yearId  The school year ID to filter classes by
     * @return JsonResponse JSON response with classes data and metadata
     *
     * @throws \Exception If database query fails
     */
    public function getClassesByYear(int $yearId): JsonResponse
    {
        try {
            $classes = Classe::where('year_id', $yearId)
                ->orderBy('label')
                ->pluck('label', 'id');

            return response()->json([
                'success' => true,
                'classes' => $classes,
                'count' => $classes->count(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des classes',
                'classes' => [],
                'count' => 0,
            ], 500);
        }
    }

    /**
     * Import students from Excel/CSV file.
     *
     * Validates the uploaded file and processes it using the StudentsImport class.
     * Handles both successful imports and errors gracefully.
     *
     * @param  Request  $request  The HTTP request containing the uploaded file
     * @return RedirectResponse Redirect to students index with success/error message
     *
     * @throws \Exception If import process fails
     */
    public function import(Request $request): RedirectResponse
    {
        try {
            // Validate the uploaded file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv,txt|max:10240',
            ], [
                'file.required' => 'Veuillez sélectionner un fichier à importer.',
                'file.mimes' => 'Le fichier doit être au format Excel (.xlsx, .xls) ou CSV.',
                'file.max' => 'Le fichier ne doit pas dépasser 10MB.',
            ]);

            $file = $request->file('file');

            // Log the import attempt
            Log::info('Starting student import from file: '.$file->getClientOriginalName());

            // Import the file using Laravel Excel
            $import = new StudentsImport;
            Excel::import($import, $file);

            // Get import statistics
            $importedCount = $import->getRowCount();
            $errors = $import->errors();
            $failures = $import->failures();

            // Prepare success message
            $message = 'Import terminé avec succès ! ';
            $message .= "{$importedCount} étudiant(s) importé(s).";

            if (! empty($errors)) {
                $message .= ' '.count($errors).' erreur(s) rencontrée(s).';
            }

            if (! empty($failures)) {
                $message .= ' '.count($failures)." ligne(s) ignorée(s) à cause d'erreurs de validation.";
            }

            Log::info("Student import completed: {$importedCount} students imported");

            return redirect()->route('students.index')
                ->with('success', $message);
        } catch (ValidationException $e) {
            // Handle validation errors (Maatwebsite specific)
            $failures = $e->failures();
            $errorMessage = "Erreurs de validation détectées :\n";

            foreach ($failures as $failure) {
                $errorMessage .= "Ligne {$failure->row()}: ".implode(', ', $failure->errors())."\n";
            }

            Log::error('Student import validation errors: '.$errorMessage);

            return redirect()->back()
                ->with('error', 'Erreurs de validation dans le fichier. Veuillez vérifier les données et réessayer.')
                ->with('validation_errors', $failures);
        } catch (\Throwable $e) {
            // CATCH TOUJOURS EN DERNIER RESSORT : Gère toutes les autres erreurs fatales (y compris TypeErrors)
            // La gestion des erreurs doit TOUJOURS se terminer par un return pour le type hint.
            Log::error('Student import failed: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'import. Veuillez vérifier le format du fichier et réessayer. Détail : '.$e->getMessage());
        }
    }
}
