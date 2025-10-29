<?php

namespace App\Imports;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentImport;
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

    private $studentImport;

    /**
     * Create a new instance.
     *
     * @param  StudentImport|null  $studentImport  The import record to track
     */
    public function __construct(?StudentImport $studentImport = null)
    {
        $this->studentImport = $studentImport;
    }

    /**
     * Save error to database.
     *
     * @param  int  $rowNumber  The row number that failed
     * @param  string  $errorType  Type of error
     * @param  string  $errorMessage  Error message
     * @param  array  $rowData  The row data that failed
     */
    private function saveError(int $rowNumber, string $errorType, string $errorMessage, array $rowData): void
    {
        if ($this->studentImport) {
            $this->studentImport->errors()->create([
                'row_number' => $rowNumber,
                'error_type' => $errorType,
                'error_message' => $errorMessage,
                'row_data' => $rowData,
            ]);
        }
    }

    /**
     * Handle validation failures during import.
     *
     * @param  Failure  ...$failures  Array of validation failures
     */
    public function onFailure(Failure ...$failures): void
    {
        // Group failures by row number
        $groupedFailures = [];
        foreach ($failures as $failure) {
            $rowNumber = $failure->row();
            if (! isset($groupedFailures[$rowNumber])) {
                $groupedFailures[$rowNumber] = [
                    'errors' => [],
                    'values' => $failure->values(),
                ];
            }
            $groupedFailures[$rowNumber]['errors'] = array_merge(
                $groupedFailures[$rowNumber]['errors'],
                $failure->errors()
            );
        }

        // Save one record per row with combined messages
        foreach ($groupedFailures as $rowNumber => $grouped) {
            $combinedMessage = implode('; ', array_unique($grouped['errors']));

            $this->saveError(
                $rowNumber,
                'validation',
                $combinedMessage,
                $grouped['values']
            );

            Log::warning('Échec de validation détecté', [
                'row' => $rowNumber,
                'errors' => $grouped['errors'],
                'values' => $grouped['values'],
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
            'situation' => $normalizedRow['situation'] ?? '',
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
                $errorMessage = 'Champs requis manquants (nom ou matricule)';

                $this->saveError($rowId, 'missing_field', $errorMessage, $row);

                Log::warning('Ligne ignorée : champs requis manquants', [
                    'name' => $name,
                    'matricule' => $matricule,
                    'row_data' => $row,
                ]);

                return null;
            }

            $schoolYearId = $this->getSchoolYearId($row);
            if (! $schoolYearId) {
                $errorMessage = 'Impossible de déterminer l\'année scolaire';

                $this->saveError($rowId, 'school_year', $errorMessage, $row);

                Log::error('Impossible de déterminer l\'année scolaire', [
                    'name' => $name,
                    'matricule' => $matricule,
                    'row_data' => $row,
                ]);

                return null;
            }

            $classeId = $this->getClasseId($row, $schoolYearId);
            if (! $classeId) {
                $errorMessage = 'Impossible de déterminer la classe';

                $this->saveError($rowId, 'class', $errorMessage, $row);

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
            $errorMessage = 'Erreur de base de données: '.$e->getMessage();

            $this->saveError($rowId, 'database', $errorMessage, $row);

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
     * @return string|null Validated situation (R/NR) or null if invalid
     */
    private function parseSituation(string $value): ?string
    {
        $situation = strtoupper(trim($value));

        return in_array($situation, ['R', 'NR']) ? $situation : null;
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
            'name' => 'required|string|max:255',
            'matricule' => 'required|string|max:50|unique:students,matricule',
            'date_of_birth' => 'nullable',
            'place_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|string|in:M,F',
            'situation' => 'required|string|in:R,NR',
            'classe' => 'required|string|max:255',
            'annee_scolaire' => 'required|string|max:20',
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
            'name.required' => 'Le nom de l\'étudiant est requis.',
            'name.max' => 'Le nom ne peut pas dépasser 255 caractères.',
            'matricule.required' => 'Le matricule de l\'étudiant est requis.',
            'matricule.max' => 'Le matricule ne peut pas dépasser 50 caractères.',
            'matricule.unique' => 'Le matricule :input existe déjà.',
            'place_of_birth.max' => 'Le lieu de naissance ne peut pas dépasser 255 caractères.',
            'gender.required' => 'Le genre est requis.',
            'gender.in' => 'Le genre doit être M (Masculin) ou F (Féminin).',
            'situation.required' => 'La situation est requise.',
            'situation.in' => 'La situation doit être R (Redoublant) ou NR (Non-Redoublant).',
            'classe.required' => 'Le nom de la classe est requis.',
            'classe.max' => 'Le nom de la classe ne peut pas dépasser 255 caractères.',
            'annee_scolaire.required' => 'L\'année scolaire est requise.',
            'annee_scolaire.max' => 'L\'année scolaire ne peut pas dépasser 20 caractères.',
        ];
    }

    /**
     * Get the total number of rows processed.
     *
     * @return int Total number of rows
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
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
     * Get a summary of the import process.
     *
     * @return array Summary with success count and failed count
     */
    public function getSummary(): array
    {
        $failedCount = $this->studentImport
            ? $this->studentImport->errors()->count()
            : 0;

        return [
            'success' => $this->successCount,
            'failed' => $failedCount,
        ];
    }
}
