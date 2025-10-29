<?php

namespace App\Http\Controllers;

use App\Models\StudentImport;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentImportController extends Controller
{
    /**
     * Display the import page with upload form and history.
     *
     * @return View The import page with upload form and history
     */
    public function index(Request $request): View
    {
        $query = StudentImport::with('user')->withCount('errors');

        // Handle sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSortFields = ['created_at', 'filename', 'total_rows', 'success_count', 'failed_count', 'status'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $imports = $query->paginate(20);

        return view('students.imports.import', compact('imports', 'sortBy', 'sortOrder'));
    }

    /**
     * Display the detailed results of a specific import.
     *
     * @param  int|string  $id  The import ID
     * @return View The import results view
     */
    public function show($id, Request $request): View
    {
        // Load the import with relationships
        $studentImport = StudentImport::with('user')->findOrFail($id);

        // Build query for errors with sorting
        $query = $studentImport->errors();

        // Handle sorting
        $sortBy = $request->get('sort_by', 'row_number');
        $sortOrder = $request->get('sort_order', 'asc');
        $allowedSortFields = ['row_number', 'error_type', 'error_message', 'created_at'];

        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Paginate errors
        $errors = $query->paginate(50);

        return view('students.imports.result', [
            'import' => $studentImport,
            'errors' => $errors,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
        ]);
    }

    /**
     * Handle the file upload and process the import.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(\Illuminate\Http\Request $request)
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

            \Illuminate\Support\Facades\Log::info('Starting student import from file: '.$file->getClientOriginalName());

            // Create import record
            $studentImport = StudentImport::create([
                'user_id' => auth()->id(),
                'filename' => $file->getClientOriginalName(),
                'status' => 'processing',
                'started_at' => now(),
            ]);

            // Perform the import
            $import = new \App\Imports\StudentsImport($studentImport);
            \Maatwebsite\Excel\Facades\Excel::import($import, $file);

            // Get summary
            $summary = $import->getSummary();
            $successCount = $summary['success'];
            $failedCount = $summary['failed'];
            $totalRows = $import->getRowCount() > 0 ? $import->getRowCount() : ($successCount + $failedCount);

            // Update import record
            $studentImport->update([
                'total_rows' => $totalRows,
                'success_count' => $successCount,
                'failed_count' => $failedCount,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            \Illuminate\Support\Facades\Log::info("Student import completed: {$successCount} students imported, {$failedCount} failures");

            // Redirect to results page
            return redirect()->route('students-import.show', $studentImport->id)
                ->with('success', 'Import terminé ! Consultez les détails ci-dessous.');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Student import failed: '.$e->getMessage());
            \Illuminate\Support\Facades\Log::error('Stack trace: '.$e->getTraceAsString());

            // Update import record to failed status if it exists
            if (isset($studentImport)) {
                $studentImport->update([
                    'status' => 'failed',
                    'completed_at' => now(),
                ]);
            }

            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'import. Veuillez vérifier le format du fichier et réessayer. Détail : '.$e->getMessage());
        }
    }
}
