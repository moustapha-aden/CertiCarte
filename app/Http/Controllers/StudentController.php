<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Controller for managing students (Student CRUD operations)
 *
 * @package App\Http\Controllers
 */
class StudentController extends Controller
{
    /**
     * Display a listing of students with optional filters.
     *
     * @param Request $request The HTTP request containing optional filter parameters
     * @return View The students index view with paginated students
     */
    public function index(Request $request): View
    {
        // Get all school years for the filter dropdown
        $allYears = SchoolYear::select('id', 'year')
            ->orderBy('year', 'desc')
            ->pluck('year', 'id');

        $selectedYearId = $request->input('year');
        $classesByYear = collect();

        // Get classes for the selected year
        if ($selectedYearId) {
            $classesByYear = Classe::where('year_id', $selectedYearId)
                ->pluck('label', 'id');
        }

        // Build the students query
        $students = Student::with('classe');

        // Filter by school year
        if ($selectedYearId) {
            $validClassIds = Classe::where('year_id', $selectedYearId)->pluck('id');
            $students->whereIn('class_id', $validClassIds);
        }

        // Filter by specific class
        if ($request->filled('class_id')) {
            $students->where('class_id', $request->input('class_id'));
        }

        // Search by name or matricule
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $students->where(function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm)
                    ->orWhere('matricule', 'like', $searchTerm);
            });
        }

        $students = $students->paginate(10)->withQueryString();

        return view('students.index', [
            'students' => $students,
            'allYears' => $allYears,
            'classesByYear' => $classesByYear,
        ]);
    }

    /**
     * Show the form for creating a new student.
     *
     * @return View The student creation form view
     */
    public function create(): View
    {
        // Get classes for the current school year
        $currentYearStr = date('Y') . '-' . (date('Y') + 1);
        $currentYear = SchoolYear::where('year', $currentYearStr)->first();

        if ($currentYear) {
            $classes = Classe::where('year_id', $currentYear->id)->get();
        } else {
            $classes = collect();
        }

        return view('students.create', compact('classes'));
    }

    /**
     * Store a newly created student in storage.
     *
     * @param StoreStudentRequest $request The validated request containing student data
     * @return RedirectResponse Redirect to students list with success/error message
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
            Log::info('Nouvel étudiant créé: ' . $student->name . ' (ID: ' . $student->id . ')');

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "' . $student->name . '" a été ajouté avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la création de l\'étudiant: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de l\'étudiant. Veuillez réessayer.');
        }
    }

    /**
     * Display the specified student.
     *
     * @param Student $student The student to display
     * @return View The student details view
     */
    public function show(Student $student): View
    {
        $student->load('classe');
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param Student $student The student to edit
     * @return View The student edit form view
     */
    public function edit(Student $student): View
    {
        $classes = Classe::pluck('label', 'id');
        return view('students.edit', compact('student', 'classes'));
    }

    /**
     * Update the specified student in storage.
     *
     * @param UpdateStudentRequest $request The validated request containing updated student data
     * @param Student $student The student to update
     * @return RedirectResponse Redirect to students list with success/error message
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
            Log::info('Étudiant modifié: ' . $student->name . ' (ID: ' . $student->id . ')');

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "' . $student->name . '" a été mis à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'étudiant: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour de l\'étudiant. Veuillez réessayer.');
        }
    }

    /**
     * Remove the specified student from storage.
     *
     * @param Student $student The student to delete
     * @return RedirectResponse Redirect to students list with success/error message
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
            Log::info('Étudiant supprimé: ' . $studentName . ' (ID: ' . $studentId . ')');

            return redirect()->route('students.index')
                ->with('success', 'L\'étudiant "' . $studentName . '" a été supprimé avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de l\'étudiant: ' . $e->getMessage());
            return redirect()->route('students.index')
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'étudiant. Veuillez réessayer.');
        }
    }
}
