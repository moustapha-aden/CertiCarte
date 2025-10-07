<?php

namespace App\Imports;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements SkipsOnError, SkipsOnFailure, ToCollection, WithHeadingRow, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    /**
     * Track the number of successfully imported rows.
     *
     * @var int
     */
    private $rowCount = 0;

    /**
     * Import students from Excel/CSV file.
     *
     * Processes each row of the uploaded file and creates student records.
     * Handles various column name formats (French/English) for flexibility.
     * Automatically creates classes if they don't exist.
     * Skips empty rows and handles validation errors gracefully.
     *
     * @param  Collection  $rows  Collection of rows from the Excel/CSV file
     * @return void
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // Skip empty rows
                if (empty($row['nom']) && empty($row['name'])) {
                    continue;
                }

                // Get student name (support both French and English headers)
                $name = $row['nom'] ?? $row['name'] ?? null;
                if (! $name) {
                    continue;
                }

                // Get matricule
                $matricule = $row['matricule'] ?? $row['student_id'] ?? null;

                // Get date of birth
                $dateOfBirth = $row['date_de_naissance'] ?? $row['date_of_birth'] ?? $row['birth_date'] ?? null;
                if ($dateOfBirth) {
                    try {
                        $dateOfBirth = \Carbon\Carbon::parse($dateOfBirth)->format('Y-m-d');
                    } catch (\Exception $e) {
                        Log::warning('Invalid date format in import data: '.$e->getMessage());
                        $dateOfBirth = null;
                    }
                }

                // Get gender
                $gender = $row['genre'] ?? $row['gender'] ?? $row['sexe'] ?? null;
                if ($gender) {
                    $gender = strtolower(trim($gender));
                    if (in_array($gender, ['M', 'm', 'masculin', 'male', 'homme'])) {
                        $gender = 'M';
                    } elseif (in_array($gender, ['F', 'f', 'feminin', 'female', 'femme'])) {
                        $gender = 'F';
                    } else {
                        $gender = null;
                    }
                }

                // Get classe name
                $classeName = $row['classe'] ?? $row['class'] ?? $row['class_name'] ?? null;
                $classeId = null;

                if ($classeName) {
                    // Find or create the classe
                    $classe = Classe::where('label', $classeName)->first();

                    if (! $classe) {
                        // Create new classe if it doesn't exist
                        // We need a school year ID for the classe
                        $schoolYearId = $this->getSchoolYearId($row);

                        $classe = Classe::create([
                            'label' => $classeName,
                            'year_id' => $schoolYearId,
                        ]);
                    }

                    $classeId = $classe->id;
                }

                // Get school year ID
                $schoolYearId = $this->getSchoolYearId($row);

                // Get situation
                $situation = $row['situation'] ?? $row['status'] ?? null;

                // Get year (for backward compatibility)
                $year = $row['annee'] ?? $row['year'] ?? null;

                // Create student data array
                $studentData = [
                    'name' => trim($name),
                    'matricule' => $matricule ? trim($matricule) : null,
                    'date_of_birth' => $dateOfBirth,
                    'gender' => $gender,
                    'situation' => $situation ? trim($situation) : null,
                    'classe_id' => $classeId,
                    'year' => $year ? trim($year) : null,
                    'school_year_id' => $schoolYearId,
                ];

                // Remove null values
                $studentData = array_filter($studentData, function ($value) {
                    return $value !== null && $value !== '';
                });

                // Create the student
                Student::create($studentData);
                $this->rowCount++;
            } catch (\Exception $e) {
                Log::warning('Error processing import row: '.$e->getMessage());
            }
        }
    }

    /**
     * Get school year ID from row data or use current year.
     *
     * Attempts to extract school year from various possible column names.
     * Falls back to the most recent school year if none found.
     *
     * @param  Collection  $row  The row data from the Excel/CSV file
     * @return int|null School year ID or null if none available
     */
    private function getSchoolYearId($row)
    {
        // Try to get school year from various possible column names
        $schoolYearValue = $row['annee_scolaire'] ?? $row['school_year'] ?? $row['year'] ?? null;

        if ($schoolYearValue) {
            // Try to find existing school year
            $schoolYear = SchoolYear::where('year', $schoolYearValue)->first();
            if ($schoolYear) {
                return $schoolYear->id;
            }
        }

        // Fallback: use the most recent school year
        $latestSchoolYear = SchoolYear::orderBy('year', 'desc')->first();

        return $latestSchoolYear ? $latestSchoolYear->id : null;
    }

    /**
     * Define validation rules for the import.
     *
     * Provides comprehensive validation rules for all possible column formats.
     * Supports both French and English column names for maximum flexibility.
     *
     * @return array Validation rules array
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'matricule' => 'nullable|string|max:50|unique:students,matricule',
            'student_id' => 'nullable|string|max:50|unique:students,matricule',
            'date_de_naissance' => 'nullable|date',
            'date_of_birth' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'genre' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme,M,F',
            'gender' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme,M,F',
            'sexe' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme,M,F',
            'classe' => 'nullable|string|max:255',
            'class' => 'nullable|string|max:255',
            'class_name' => 'nullable|string|max:255',
            'situation' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'annee_scolaire' => 'nullable|string|max:255',
            'school_year' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:255',
            'annee' => 'nullable|string|max:255',
        ];
    }

    /**
     * Custom validation messages.
     *
     * Provides localized French error messages for validation failures.
     * Covers all possible column formats and validation scenarios.
     *
     * @return array Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            'nom.required' => 'Le nom de l\'étudiant est requis.',
            'name.required' => 'Le nom de l\'étudiant est requis.',
            'matricule.unique' => 'Le matricule :input existe déjà.',
            'student_id.unique' => 'Le matricule :input existe déjà.',
            'date_de_naissance.date' => 'La date de naissance doit être une date valide.',
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'birth_date.date' => 'La date de naissance doit être une date valide.',
            'genre.in' => 'Le genre doit être masculin ou féminin.',
            'gender.in' => 'Le genre doit être masculin ou féminin.',
            'sexe.in' => 'Le genre doit être masculin ou féminin.',
            'sexe.in' => 'Le genre doit être masculin ou féminin.',
        ];
    }

    /**
     * Get the number of successfully imported rows.
     *
     * Returns the count of students that were successfully processed
     * and saved to the database during the import operation.
     *
     * @return int Number of successfully imported students
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
