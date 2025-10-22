<?php

namespace App\Imports;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

/**
 * Import students from Excel/CSV files with support for French and English column names.
 *
 * This class handles the import of student data with the following features:
 * - Supports both French (nom, date_naissance, pays_naissance, genre) and English column names
 * - Automatically creates school years and classes if they don't exist
 * - Converts Excel numeric dates to proper date format
 * - Validates data and provides detailed error logging
 * - Skips invalid rows while continuing the import process
 */
class StudentsImport implements SkipsOnError, SkipsOnFailure, ToModel, WithCalculatedFormulas, WithCustomCsvSettings, WithHeadingRow, WithMapping, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    private $rowCount = 0;

    private $successCount = 0;

    private $failedRows = [];

    /**
     * Handle validation failures during import.
     *
     * @param  Failure  ...$failures  Array of validation failures
     */
    public function onFailure(Failure ...$failures): void
    {
        foreach ($failures as $failure) {
            $this->failedRows[] = [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
                'type' => 'validation',
            ];

            Log::warning('Échec de validation détecté', [
                'row' => $failure->row(),
                'errors' => $failure->errors(),
                'values' => $failure->values(),
            ]);
        }
    }

    /**
     * Configure CSV parsing settings.
     *
     * @return array<string, mixed> CSV configuration options
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => "\0",
            'input_encoding' => 'UTF-8',
        ];
    }

    /**
     * Map Excel columns to normalized field names.
     *
     * @param  array  $row  Raw row data from Excel
     * @return array Normalized row data
     */
    public function map($row): array
    {
        // Normalize keys: lowercase and trim them
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedRow[strtolower(trim($key))] = $value;
        }

        // ✅ Nettoyage et normalisation des valeurs avant mapping
        foreach ($normalizedRow as $key => &$value) {
            if (is_numeric($value) && ! in_array($key, ['date_naissance', 'date naissance', 'date de naissance'])) {
                $value = (string) $value; // Convertit les valeurs numériques en string
            }
            if (is_string($value)) {
                $value = trim($value);
            }
        }

        // ✅ Normalise certains champs spécifiques
        if (isset($normalizedRow['genre'])) {
            $normalizedRow['genre'] = strtoupper($normalizedRow['genre']);
        }
        if (isset($normalizedRow['situation'])) {
            $normalizedRow['situation'] = strtoupper($normalizedRow['situation']);
        }

        return [
            'name' => $normalizedRow['nom'] ?? '',
            'matricule' => $normalizedRow['matricule'] ?? '',
            'date_of_birth' => $normalizedRow['date_naissance'] ?? $normalizedRow['date naissance'] ?? $normalizedRow['date de naissance'] ?? null,
            'place_of_birth' => $normalizedRow['pays_naissance'] ?? $normalizedRow['pays naissance'] ?? $normalizedRow['pays de naissance'] ?? null,
            'gender' => $normalizedRow['genre'] ?? '',
            'situation' => $normalizedRow['situation'] ?? 'NR', // Default to NR if not specified
            'classe' => $normalizedRow['classe'] ?? $normalizedRow['classes'] ?? $normalizedRow['class'] ?? '',
            'annee_scolaire' => $normalizedRow['annee_scolaire'] ?? $normalizedRow['annee scolaire'] ?? $normalizedRow['année scolaire'] ?? '',
        ];
    }

    /**
     * Transform a row into a Student model.
     *
     * @param  array  $row  Normalized row data after mapping
     * @return Student|null Instance of created student or null on failure
     */
    public function model(array $row): ?Student
    {
        $this->rowCount++;
        $rowId = $row['id'] ?? ($this->rowCount + 1);

        try {
            $name = trim($row['name'] ?? '');
            $matricule = trim($row['matricule'] ?? '');

            if (empty($name) || empty($matricule)) {
                $this->failedRows[] = [
                    'row' => $rowId,
                    'error' => 'Champs requis manquants (nom ou matricule)',
                    'data' => $row,
                    'type' => 'missing_fields',
                ];

                Log::warning('Ligne ignorée : champs requis manquants', [
                    'name' => $name,
                    'matricule' => $matricule,
                    'row_data' => $row,
                ]);

                return null;
            }

            $schoolYearId = $this->getSchoolYearId($row);
            if (! $schoolYearId) {
                $this->failedRows[] = [
                    'row' => $rowId,
                    'error' => 'Impossible de déterminer l\'année scolaire',
                    'data' => $row,
                    'type' => 'school_year_error',
                ];

                Log::error('Impossible de déterminer l\'année scolaire', [
                    'name' => $name,
                    'matricule' => $matricule,
                    'row_data' => $row,
                ]);

                return null;
            }

            $classeId = $this->getClasseId($row, $schoolYearId);
            if (! $classeId) {
                $this->failedRows[] = [
                    'row' => $rowId,
                    'error' => 'Impossible de déterminer la classe',
                    'data' => $row,
                    'type' => 'class_error',
                ];

                Log::error('Impossible de déterminer la classe', [
                    'name' => $name,
                    'matricule' => $matricule,
                    'school_year_id' => $schoolYearId,
                    'row_data' => $row,
                ]);

                return null;
            }

            $formattedDateOfBirth = $this->parseExcelDate($row['date_of_birth'] ?? null);
            $gender = $this->parseGender($row['gender'] ?? '');
            $situation = $this->parseSituation($row['situation'] ?? 'R');

            $student = Student::create([
                'name' => $name,
                'matricule' => $matricule,
                'date_of_birth' => $formattedDateOfBirth,
                'place_of_birth' => $row['place_of_birth'] ?? null,
                'gender' => $gender,
                'situation' => $situation,
                'classe_id' => $classeId,
            ]);

            $this->successCount++;

            return $student;
        } catch (\Exception $e) {
            $this->failedRows[] = [
                'row' => $rowId,
                'error' => 'Erreur de base de données: '.$e->getMessage(),
                'data' => $row,
                'type' => 'database_error',
            ];

            Log::error('Erreur de base de données lors de la création de l\'étudiant', [
                'name' => $name ?? 'unknown',
                'matricule' => $matricule ?? 'unknown',
                'error' => $e->getMessage(),
                'row_data' => $row,
            ]);

            return null;
        }
    }

    /**
     * Get or create a class for the given school year.
     *
     * @param  array  $row  Row data containing class information
     * @param  int  $schoolYearId  ID of the school year
     * @return int|null ID of the class or null on failure
     */
    private function getClasseId(array $row, int $schoolYearId): ?int
    {
        $classeLabel = trim($row['classe'] ?? $row['label_classe'] ?? '');

        if (empty($classeLabel)) {
            Log::warning('Le nom de la classe est manquant', ['row_data' => $row]);

            return null;
        }

        try {
            $classe = Classe::firstOrCreate(
                [
                    'label' => $classeLabel,
                    'year_id' => $schoolYearId,
                ],
                [
                    'label' => $classeLabel,
                    'year_id' => $schoolYearId,
                ]
            );

            return $classe->id;
        } catch (\Exception $e) {
            Log::error('Impossible de trouver ou créer la classe', [
                'class_label' => $classeLabel,
                'school_year_id' => $schoolYearId,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Parse date value with support for multiple formats.
     *
     * @param  mixed  $value  Date value from Excel (numeric or string)
     * @return string|null Formatted date string (Y-m-d) or null on failure
     */
    private function parseExcelDate($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // ✅ Si c'est un nombre Excel (ex: 39818)
        if (is_numeric($value) && $value > 10000 && $value < 50000) {
            try {
                return Carbon::createFromDate(1899, 12, 30)->addDays((int) $value)->format('Y-m-d');
            } catch (\Exception $e) {
                Log::warning('Erreur conversion date numérique', ['value' => $value]);
            }
        }

        // ✅ Formats classiques
        $formats = ['d/m/Y', 'd-m-Y', 'd.m.Y', 'Y-m-d', 'd/m/y', 'd-m-y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, trim((string) $value))->format('Y-m-d');
            } catch (\Exception $e) {
            }
        }

        // ✅ Tentative finale
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            Log::error('Format de date non reconnu', ['value' => $value]);
        }

        return null;
    }

    /**
     * Parse and validate gender value.
     *
     * @param  string  $value  Gender value from Excel
     * @return string|null Validated gender (M/F) or null if invalid
     */
    private function parseGender(string $value): ?string
    {
        $gender = strtoupper(trim($value));

        return in_array($gender, ['M', 'F']) ? $gender : null;
    }

    /**
     * Parse and validate situation value.
     *
     * @param  string  $value  Situation value from Excel
     * @return string Validated situation (R/NR), defaults to 'R'
     */
    private function parseSituation(string $value): string
    {
        $situation = strtoupper(trim($value));

        return in_array($situation, ['R', 'NR']) ? $situation : 'R';
    }

    /**
     * Get or create school year ID from row data.
     *
     * @param  array  $row  Row data containing school year information
     * @return int|null ID of the school year or null on failure
     */
    private function getSchoolYearId(array $row): ?int
    {
        $schoolYearValue = $row['annee_scolaire'] ?? $row['school_year'] ?? $row['year'] ?? null;

        if ($schoolYearValue) {
            $schoolYearValue = trim(str_replace('"', '', $schoolYearValue));

            if (! empty($schoolYearValue)) {
                $schoolYear = SchoolYear::firstOrCreate(
                    ['year' => $schoolYearValue],
                    ['year' => $schoolYearValue]
                );

                return $schoolYear->id;
            }
        }

        $latestSchoolYear = SchoolYear::orderBy('year', 'desc')->first();
        if ($latestSchoolYear) {

            return $latestSchoolYear->id;
        }

        Log::error('Impossible de déterminer ou créer l\'année scolaire');

        return null;
    }

    /**
     * Define validation rules for imported data.
     *
     * @return array<string, string> Validation rules
     */
    public function rules(): array
    {
        return [
            'classe' => 'required|string|max:255',
            'annee_scolaire' => 'required|string|max:20',
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:50|unique:students,matricule',
            'date_of_birth' => 'nullable',
            'gender' => 'required|string|in:M,F',
            'place_of_birth' => 'nullable|string|max:255',
            'situation' => 'nullable|string|in:R,NR',
        ];
    }

    /**
     * Define custom validation messages.
     *
     * @return array<string, string> Custom validation messages
     */
    public function customValidationMessages(): array
    {
        return [
            'matricule.unique' => 'Le matricule :input existe déjà.',
            'name.required' => 'Le nom de l\'étudiant est requis.',
            'matricule.required' => 'Le matricule de l\'étudiant est requis.',
            'classe.required' => 'Le nom de la classe est requis.',
            'annee_scolaire.required' => 'L\'année scolaire est requise.',
            'gender.required' => 'Le genre est requis (M pour Masculin, F pour Féminin).',
            'gender.in' => 'Le genre doit être M (Masculin) ou F (Féminin).',
            'situation.in' => 'La situation doit être R (Redoublant) ou NR (Non-Redoublant).',
        ];
    }

    /**
     * Get the number of successfully imported students.
     *
     * @return int Number of successful imports
     */
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    /**
     * Get the array of failed rows with their details.
     *
     * @return array Array of failed rows with error details
     */
    public function getFailedRows(): array
    {
        return $this->failedRows;
    }

    /**
     * Get a summary of the import process.
     *
     * @return array Summary with success count, failed count, and failure details
     */
    public function getSummary(): array
    {
        return [
            'success' => $this->successCount,
            'failed' => count($this->failedRows),
            'failures' => $this->failedRows,
        ];
    }
}
