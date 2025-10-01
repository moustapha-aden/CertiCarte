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
                        Log::warning("Invalid date format for student {$name}: {$dateOfBirth}");
                        $dateOfBirth = null;
                    }
                }

                // Get gender
                $gender = $row['genre'] ?? $row['gender'] ?? $row['sexe'] ?? null;
                if ($gender) {
                    $gender = strtolower(trim($gender));
                    if (in_array($gender, ['m', 'masculin', 'male', 'homme'])) {
                        $gender = 'male';
                    } elseif (in_array($gender, ['f', 'feminin', 'female', 'femme'])) {
                        $gender = 'female';
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

                        Log::info("Created new classe: {$classeName} (ID: {$classe->id})");
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

                Log::info("Imported student: {$name} (Matricule: {$matricule})");
            } catch (\Exception $e) {
                Log::error('Error importing student row: '.$e->getMessage());
                Log::error('Row data: '.json_encode($row->toArray()));
                // Continue with next row
            }
        }
    }

    /**
     * Get school year ID from row data or use current year.
     *
     * @param  Collection  $row
     * @return int|null
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
            'genre' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme',
            'gender' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme',
            'sexe' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme',
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
     * @return array
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
        ];
    }

    /**
     * Get the number of successfully imported rows.
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
