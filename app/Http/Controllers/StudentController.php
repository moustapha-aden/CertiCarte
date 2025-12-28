<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportPhotosRequest;
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
     * @param  Request  $request  The HTTP request containing filter and sort parameters
     * @return View The students index view with paginated and filtered student data
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
     * @param  StoreStudentRequest  $request  The validated request containing student data
     * @return RedirectResponse Redirect to students index with success/error message
     */
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

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
     * @param  Student  $student  The student model instance to edit
     * @param  Request  $request  The HTTP request containing school year selection
     * @return View The student edit form view with pre-populated data
     */
    public function edit(Student $student, Request $request): View
    {
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        $selectedYearId = $request->input('school_year_id')
            ?? old('school_year_id')
            ?? optional($student->classe)->year_id;

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
     * @param  UpdateStudentRequest  $request  The validated request containing updated student data
     * @param  Student  $student  The student model instance to update
     * @return RedirectResponse Redirect to students index with success/error message
     */
    public function update(UpdateStudentRequest $request, Student $student): RedirectResponse
    {
        try {
            $validatedData = $request->validated();

            if ($request->hasFile('photo')) {
                if ($student->photo) {
                    Storage::disk('public')->delete($student->photo);
                }
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
     * @param  Student  $student  The student model instance to delete
     * @return RedirectResponse Redirect to students index with success/error message
     */
    public function destroy(Student $student): RedirectResponse
    {
        try {
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
     * @param  int  $yearId  The school year ID to filter classes by
     * @return JsonResponse JSON response with classes data and metadata
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
     * @param  Request  $request  The HTTP request containing the uploaded file
     * @return RedirectResponse Redirect to students index with success/error message
     */
    public function import(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xlsx,xls,csv,txt|max:10240',
            ], [
                'file.required' => 'Veuillez sélectionner un fichier à importer.',
                'file.mimes' => 'Le fichier doit être au format Excel (.xlsx, .xls) ou CSV.',
                'file.max' => 'Le fichier ne doit pas dépasser 10MB.',
            ]);

            $file = $request->file('file');

            Log::info('Starting student import from file: '.$file->getClientOriginalName());

            $import = new StudentsImport;
            Excel::import($import, $file);

            $importedCount = $import->getRowCount();
            $errors = $import->errors();
            $failures = $import->failures();

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
            Log::error('Student import failed: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'import. Veuillez vérifier le format du fichier et réessayer. Détail : '.$e->getMessage());
        }
    }

    /**
     * Import student photos in bulk.
     *
     * Processes multiple photo files, extracts matricule from filename,
     * and associates each photo with the corresponding student.
     *
     * @param  ImportPhotosRequest  $request  The validated request containing photo files
     * @return RedirectResponse Redirect to students index with detailed import report
     */
    public function importPhotos(ImportPhotosRequest $request): RedirectResponse
    {
        try {
            $photos = $request->file('photos');

            // Vérifier si tous les fichiers ont été reçus
            if (! $photos || count($photos) === 0) {
                return redirect()->back()
                    ->with('error', 'Aucun fichier n\'a été reçu. Vérifiez que les limites PHP sont configurées correctement (max_file_uploads, upload_max_filesize, post_max_size).');
            }

            $imported = 0;
            $notFound = [];
            $errors = [];
            $replaced = 0;
            $details = [];

            Log::info('Starting photos import: '.count($photos).' files');

            // Vérifier si le nombre de fichiers semble limité par PHP
            $maxFileUploads = ini_get('max_file_uploads');
            $receivedCount = count($photos);

            if ($maxFileUploads && $receivedCount >= (int) $maxFileUploads) {
                Log::warning('Le nombre de fichiers reçus atteint la limite PHP max_file_uploads: '.$maxFileUploads);
                $errors[] = '⚠️ IMPORTANT: Seulement '.$receivedCount.' fichier(s) ont été reçus. La limite PHP max_file_uploads ('.$maxFileUploads.') bloque probablement les autres fichiers. Augmentez max_file_uploads dans php.ini pour traiter tous les fichiers.';
            }

            // Augmenter le temps d'exécution pour les gros imports
            if ($receivedCount > 50) {
                set_time_limit(600); // 10 minutes pour les gros imports
                ini_set('max_execution_time', '600');
            }

            foreach ($photos as $photo) {
                try {
                    // Extract matricule from filename (without extension)
                    // Example: "12345.jpg" -> "12345"
                    $filename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $matricule = trim($filename);

                    if (empty($matricule)) {
                        $errors[] = $photo->getClientOriginalName().' (nom de fichier invalide)';
                        Log::warning('Empty matricule in filename: '.$photo->getClientOriginalName());
                        continue;
                    }

                    // Find student by matricule
                    $student = Student::where('matricule', $matricule)->first();

                    if (! $student) {
                        $notFound[] = $photo->getClientOriginalName();
                        Log::warning('Student not found for matricule: '.$matricule);
                        continue;
                    }

                    // Check if student already has a photo
                    $hadPhoto = ! empty($student->photo);

                    // Delete old photo if exists
                    if ($hadPhoto && Storage::disk('public')->exists($student->photo)) {
                        Storage::disk('public')->delete($student->photo);
                    }

                    // Store new photo
                    $photoPath = $photo->store('photos/students', 'public');

                    // Update student record
                    $student->update(['photo' => $photoPath]);

                    $imported++;
                    if ($hadPhoto) {
                        $replaced++;
                    }

                    $details[] = [
                        'filename' => $photo->getClientOriginalName(),
                        'student' => $student->name,
                        'matricule' => $matricule,
                        'replaced' => $hadPhoto,
                    ];

                    Log::info('Photo imported successfully', [
                        'filename' => $photo->getClientOriginalName(),
                        'student_id' => $student->id,
                        'matricule' => $matricule,
                        'replaced' => $hadPhoto,
                    ]);
                } catch (Exception $e) {
                    $errors[] = $photo->getClientOriginalName().' ('.$e->getMessage().')';
                    Log::error('Error processing photo: '.$photo->getClientOriginalName(), [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            // Build result message
            $totalProcessed = $receivedCount;
            $message = "Import des photos terminé ! ";
            $message .= "{$imported} photo(s) importée(s) avec succès sur {$totalProcessed} fichier(s) reçu(s).";

            if ($replaced > 0) {
                $message .= " {$replaced} photo(s) remplacée(s).";
            }

            if (count($notFound) > 0) {
                $message .= ' '.count($notFound).' photo(s) non associée(s) (matricule introuvable).';
            }

            if (count($errors) > 0) {
                $message .= ' '.count($errors).' erreur(s) technique(s).';
            }

            // Avertissement si tous les fichiers n'ont pas été reçus
            if ($maxFileUploads && $receivedCount >= (int) $maxFileUploads) {
                $message .= " ATTENTION: Si vous avez sélectionné plus de {$receivedCount} fichiers, certains n'ont peut-être pas été envoyés à cause de la limite PHP max_file_uploads ({$maxFileUploads}).";
            }

            Log::info("Photos import completed: {$imported} imported, ".count($notFound).' not found, '.count($errors).' errors');

            // Prepare session data for detailed report
            $reportData = [
                'imported' => $imported,
                'received' => $receivedCount,
                'replaced' => $replaced,
                'not_found' => $notFound,
                'errors' => $errors,
                'details' => $details,
                'max_file_uploads' => $maxFileUploads ? (int) $maxFileUploads : null,
            ];

            return redirect()->route('students.index')
                ->with('success', $message)
                ->with('photos_import_report', $reportData);
        } catch (\Throwable $e) {
            Log::error('Photos import failed: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'import des photos. Veuillez réessayer. Détail : '.$e->getMessage());
        }
    }
}
