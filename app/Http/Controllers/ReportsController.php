<?php

namespace App\Http\Controllers;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for generating various reports and documents.
 *
 * Handles the generation of student certificates, ID cards, and attendance lists
 * through a unified reports interface with dynamic form handling.
 *
 * @author Lycée Ahmed Farah Ali
 *
 * @version 1.0.0
 */
class ReportsController extends Controller
{
    /**
     * Display the reports page with dynamic form for report generation.
     *
     * Shows a step-based interface for selecting report type and parameters.
     * Loads initial data for school years and classes.
     *
     * @return View The reports page view
     */
    public function index(): View
    {
        // Get all school years for the dropdown
        $schoolYears = SchoolYear::orderBy('year', 'desc')->get();

        // Get all classes for the dropdown
        $classes = Classe::with('schoolYear')->orderBy('label')->get();

        return view('reports.index', compact('schoolYears', 'classes'));
    }

    /**
     * Generate a student certificate PDF.
     *
     * Creates a school enrollment certificate using DomPDF library.
     * Includes student information, school details, and current date.
     * Returns the PDF as a stream for inline browser display.
     *
     * @param  Request  $request  The HTTP request containing report parameters
     * @return Response|RedirectResponse PDF stream or redirect with error
     *
     * @throws Exception If PDF generation fails
     */
    public function generateCertificate(Student $student)
    {
        try {
            // Get school year information
            $schoolYearObject = optional($student->classe)->schoolYear;
            $school_year = $schoolYearObject ? $schoolYearObject->year : 'Année Inconnue';

            $currentDate = Carbon::now();

            // Load the certificate view with data
            $pdf = Pdf::loadView('reports.certificate', compact('student', 'school_year', 'currentDate'));

            // Define filename for download
            $filename = 'Certificat_Scolarite_'.$student->matricule.'_'.$currentDate->format('Ymd').'.pdf';

            // Stream the PDF for inline display
            return $pdf->stream($filename);
        } catch (Exception $e) {
            Log::error('Certificate generation failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération du certificat: '.$e->getMessage());
        }
    }

    /**
     * Generate a student ID card PDF.
     *
     * Creates a professional student ID card using DomPDF library.
     * Includes student photo, personal information, school details, and security features.
     * Handles missing photos with fallback avatars gracefully.
     *
     * @param  Request  $request  The HTTP request containing report parameters
     * @return Response|RedirectResponse PDF stream or redirect with error
     *
     * @throws Exception If PDF generation fails
     */
    public function generateIdCard(Student $student)
    {
        try {

            // Load necessary relationships
            $student->load('classe.schoolYear');

            // School information
            $lyceeInfo = [
                'name' => 'Lycée Ahmed Farah Ali',
                'country' => 'République de Djibouti',
            ];

            // Student photo
            $avatar = $this->getStudentPhoto($student);

            // School year
            $school_year = optional($student->classe->schoolYear)->year ?? 'Année Inconnue';

            // Current date
            $currentDate = Carbon::now();

            // School background image (Base64)
            $lyceePhotoUrl = null;
            try {
                $path = public_path('images/lycee_balbala.jpg');
                if (file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $lyceePhotoUrl = 'data:image/'.$type.';base64,'.base64_encode($data);
                }
            } catch (Exception $e) {
                Log::warning('Optional background image not found: '.$e->getMessage());
            }

            // School logo (photo_carte.jpg)
            $logoUrl = null;
            try {
                $logoPath = public_path('images/photo_carte.jpg');
                if (file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoUrl = 'data:image/'.$type.';base64,'.base64_encode($data);
                }
            } catch (Exception $e) {
                Log::warning('Optional school logo not found: '.$e->getMessage());
            }

            // Generate PDF
            $pdf = Pdf::loadView('reports.id-card', compact(
                'student',
                'lyceeInfo',
                'avatar',
                'school_year',
                'currentDate',
                'lyceePhotoUrl',
                'logoUrl'
            ));

            // Filename
            $filename = 'Carte_Etudiant_'.$student->matricule.'_'.$currentDate->format('Ymd').'.pdf';

            // Return PDF
            return $pdf->stream($filename);
        } catch (Exception $e) {
            Log::error('ID card generation failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération de la carte d\'étudiant: '.$e->getMessage());
        }
    }

    /**
     * Generate an attendance list PDF for a class.
     *
     * Creates a printable attendance list using DomPDF library.
     * Supports both single-day and two-day formats based on the 'days' parameter.
     * Includes student names, dates, and class information.
     *
     * @param  Request  $request  The HTTP request containing report parameters
     * @return Response|RedirectResponse PDF stream or redirect with error
     *
     * @throws Exception If PDF generation fails
     */
    public function generateAttendanceList(Classe $classe)
    {
        try {

            // Get the 'days' parameter from query string (default to 1)
            $days = request()->query('days', 1);

            // Ensure 'days' is 1 or 2
            if (! in_array($days, [1, 2])) {
                return redirect()->back()->with('error', 'Le nombre de jours doit être 1 ou 2.');
            }

            // Get students from the class, sorted by name
            $students = $classe->students()->orderBy('name')->get();

            // Calculate necessary dates
            $dates = [];
            $today = Carbon::now();
            $dates[] = $today->format('d/m/Y');

            if ($days == 2) {
                $tomorrow = $today->copy()->addDay();
                $dates[] = $tomorrow->format('d/m/Y');
            }

            // Generate PDF with appropriate view
            if ($days == 2) {
                $pdf = Pdf::loadView('reports.attendance-list-2days', compact('classe', 'students', 'dates'));
            } else {
                $pdf = Pdf::loadView('reports.attendance-list', compact('classe', 'students', 'dates'));
            }

            // Define filename
            $filename = 'Liste_Appel_'.$classe->label.'_'.$today->format('Ymd').'.pdf';

            // Stream the PDF
            return $pdf->stream($filename);
        } catch (Exception $e) {
            Log::error('Attendance list generation failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Erreur lors de la génération de la liste d\'appel: '.$e->getMessage());
        }
    }

    /**
     * Get students filtered by class for AJAX requests.
     *
     * Returns a JSON response with students belonging to the specified class.
     * Used for dynamic dropdown population in the reports form.
     *
     * @param  int  $classeId  The class ID
     * @return JsonResponse JSON response with students data
     */
    public function getStudentsByClass(int $classeId): JsonResponse
    {
        try {
            Log::info('API: Loading students for class ID: '.$classeId);

            $students = Student::where('classe_id', $classeId)
                ->orderBy('name')
                ->get(['id', 'name', 'matricule']);

            Log::info('API: Found '.$students->count().' students for class '.$classeId);

            return response()->json([
                'success' => true,
                'students' => $students,
            ]);
        } catch (Exception $e) {
            Log::error('API: Error loading students for class '.$classeId.': '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du chargement des étudiants',
            ], 500);
        }
    }

    /**
     * Get student photo with fallback to default avatar.
     *
     * Attempts to load the student's uploaded photo from storage.
     * If no photo exists or loading fails, falls back to default avatar.
     * If default avatar is also missing, generates a colored SVG avatar.
     *
     * @param  Student  $student  The student to get photo for
     * @return string Base64 encoded image data
     */
    private function getStudentPhoto(Student $student): string
    {
        try {
            if ($student->photo && Storage::disk('public')->exists($student->photo)) {
                $path = Storage::disk('public')->path($student->photo);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);

                return 'data:image/'.$type.';base64,'.base64_encode($data);
            }
        } catch (Exception $e) {
            Log::warning('Student photo not found: '.$e->getMessage());
        }

        // Fallback to generated avatar
        return $this->generateFallbackAvatar($student);
    }

    /**
     * Generate a fallback SVG avatar for students without photos.
     *
     * Creates a colored SVG avatar using the student's initials.
     * Uses consistent colors based on the student's name.
     *
     * @param  Student  $student  The student to generate avatar for
     * @return string Base64 encoded SVG data
     */
    private function generateFallbackAvatar(Student $student): string
    {
        $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6'];
        $color = $colors[ord(substr($student->name, 0, 1)) % count($colors)];

        $svg = '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
            <rect width="100" height="100" fill="'.$color.'"/>
            <text x="50" y="50" font-family="Arial" font-size="40" fill="white" text-anchor="middle" dominant-baseline="middle">'
            .strtoupper(substr($student->name, 0, 1)).'</text>
        </svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * Generate a unique card number for the student.
     *
     * Creates a unique identifier combining student ID, matricule, and current date.
     * Uses MD5 hash for security and consistency.
     *
     * @param  Student  $student  The student to generate card number for
     * @param  Carbon  $date  The current date for uniqueness
     * @return string Unique card number with 'ID-' prefix
     */
    private function generateCardNumber(Student $student, Carbon $date): string
    {
        $base = $student->id.$student->matricule.$date->format('Ymd');

        return 'ID-'.strtoupper(substr(md5($base), 0, 8));
    }
}
