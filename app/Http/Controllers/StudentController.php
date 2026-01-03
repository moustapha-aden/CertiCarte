<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportPhotosRequest;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
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
        // Get all school years for the filter dropdown
        $schoolYears = SchoolYear::orderBy('year', 'desc')->pluck('year', 'id');

        // Get classes for the filter dropdown (filtered by school year if selected)
        $allClasses = collect();
        if ($request->filled('year_id')) {
            $allClasses = Classe::where('year_id', $request->input('year_id'))
                ->select('id', 'label')
                ->orderBy('label')
                ->pluck('label', 'id');
        }

        // Build the students query
        $students = Student::with(['classe', 'classe.schoolYear']);

        // Filter by school year (through classe relationship)
        if ($request->filled('year_id')) {
            $students->whereHas('classe', function ($query) use ($request) {
                $query->where('year_id', $request->input('year_id'));
            });
        }

        // Filter by specific class
        if ($request->filled('classe_id')) {
            $students->where('classe_id', $request->input('classe_id'));
        }

        // Simple search (searches name or matricule)
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
            'schoolYears' => $schoolYears,
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

            // Check if any files were actually received by PHP
            if (! $photos || (is_array($photos) && count($photos) === 0)) {
                Log::error('No photos received in request', [
                    'has_photos' => !empty($photos),
                    'photos_count' => is_array($photos) ? count($photos) : 'not_array',
                    'request_keys' => array_keys($request->all()),
                ]);

                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Aucun fichier n\'a été reçu. Vérifiez que les limites PHP sont configurées correctement (max_file_uploads, upload_max_filesize, post_max_size).');
            }

            $imported = 0;
            $notFound = [];
            $errors = [];
            $replaced = 0;
            $details = [];
            $receivedCount = count($photos); // Track actual received files

            Log::info('Starting photos import: '.$receivedCount.' files');

            // Warning if the number of received files seems limited by PHP configuration
            $maxFileUploads = ini_get('max_file_uploads');
            if ($maxFileUploads && $receivedCount >= (int) $maxFileUploads) {
                Log::warning('The number of received files reached PHP max_file_uploads limit: '.$maxFileUploads);
                $errors[] = 'Avertissement: Le nombre de fichiers reçus ('.$receivedCount.') atteint la limite PHP max_file_uploads ('.$maxFileUploads.'). Certains fichiers peuvent ne pas avoir été envoyés.';
            }

            foreach ($photos as $photo) {
                try {
                    // Vérifier que c'est bien un fichier uploadé valide
                    if (! $photo || ! $photo->isValid()) {
                        $errors[] = ($photo ? $photo->getClientOriginalName() : 'fichier inconnu').' (fichier invalide ou corrompu)';
                        Log::warning('Invalid photo file', [
                            'file' => $photo ? $photo->getClientOriginalName() : 'null',
                            'is_valid' => $photo ? $photo->isValid() : false,
                        ]);
                        continue;
                    }

                    $filename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $matricule = trim($filename);

                    if (empty($matricule)) {
                        $errors[] = $photo->getClientOriginalName().' (nom de fichier invalide)';
                        Log::warning('Empty matricule in filename: '.$photo->getClientOriginalName());
                        continue;
                    }

                    $student = Student::where('matricule', $matricule)->first();

                    if (! $student) {
                        $notFound[] = $photo->getClientOriginalName();
                        Log::warning('Student not found for matricule: '.$matricule);
                        continue;
                    }

                    $hadPhoto = ! empty($student->photo);

                    if ($hadPhoto && Storage::disk('public')->exists($student->photo)) {
                        Storage::disk('public')->delete($student->photo);
                    }

                    // Stocker la photo avec le matricule comme nom de fichier pour faciliter la gestion
                    $extension = $photo->getClientOriginalExtension();
                    $photoPath = $photo->storeAs('photos/students', $matricule.'.'.$extension, 'public');

                    // Vérifier que le stockage a réussi
                    if (! $photoPath) {
                        throw new Exception('Échec du stockage du fichier : '.$photo->getClientOriginalName());
                    }

                    Log::info('Photo stored successfully', [
                        'matricule' => $matricule,
                        'photo_path' => $photoPath,
                        'student_id' => $student->id,
                        'original_filename' => $photo->getClientOriginalName(),
                    ]);

                    // Mettre à jour l'étudiant
                    $updated = $student->update(['photo' => $photoPath]);

                    // Vérifier que l'update a réussi
                    if (! $updated) {
                        // Supprimer le fichier stocké si l'update a échoué
                        Storage::disk('public')->delete($photoPath);
                        throw new Exception('Échec de la mise à jour de l\'étudiant : '.$matricule);
                    }

                    $imported++;
                    if ($hadPhoto) {
                        $replaced++;
                    }
                    $details[] = [
                        'filename' => $photo->getClientOriginalName(),
                        'matricule' => $matricule,
                        'status' => 'success',
                        'replaced' => $hadPhoto,
                    ];
                } catch (Exception $e) {
                    $errors[] = $photo->getClientOriginalName().' ('.$e->getMessage().')';
                    Log::error('Error processing photo: '.$photo->getClientOriginalName(), [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            }

            $message = "Import des photos terminé ! ";
            $message .= "{$imported} photo(s) importée(s) avec succès sur {$receivedCount} fichier(s) reçu(s).";

            if ($replaced > 0) {
                $message .= " {$replaced} photo(s) remplacée(s).";
            }

            if (count($notFound) > 0) {
                $message .= ' '.count($notFound).' photo(s) non associée(s) (matricule introuvable).';
            }

            if (count($errors) > 0) {
                $message .= ' '.count($errors).' erreur(s) technique(s).';
            }

            Log::info("Photos import completed: {$imported} imported, ".count($notFound).' not found, '.count($errors).' errors');

            $reportData = [
                'received' => $receivedCount,
                'imported' => $imported,
                'replaced' => $replaced,
                'not_found' => $notFound,
                'errors' => $errors,
                'details' => $details,
                'max_file_uploads' => (int) $maxFileUploads,
            ];

            // Redirect based on where the request came from
            $redirectRoute = request()->header('Referer') && str_contains(request()->header('Referer'), route('classes.index', [], false))
                ? 'classes.index'
                : 'students.index';

            return redirect()->route($redirectRoute)
                ->with('success', $message)
                ->with('photos_import_report', $reportData);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Photo import validation failed', [
                'errors' => $e->errors(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors())
                ->with('error', 'Erreur de validation : Veuillez vérifier les fichiers sélectionnés.');
        } catch (\Throwable $e) {
            Log::error('Bulk photo import failed: '.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'import des photos. Veuillez réessayer. Détail : '.$e->getMessage());
        }
    }
}
