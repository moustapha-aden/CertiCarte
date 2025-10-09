<?php

namespace App\Imports;

use App\Models\Classe;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StudentsImport implements SkipsOnError, SkipsOnFailure, ToCollection, WithCustomCsvSettings, WithHeadingRow, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    private $rowCount = 0;

    /**
     * Paramètres pour CSV (utile si tu importes aussi des CSV)
     */
    public function getCsvSettings(): array
    {
        Log::debug('CSV Settings loaded.'); // LOG: Chargement des paramètres CSV
        return [
            'delimiter' => ',',
            'enclosure' => "\0",
            'input_encoding' => 'UTF-8',
        ];
    }

    public function collection(Collection $rows)
    {
        Log::debug('Starting student import. Total rows to process: ' . $rows->count()); // LOG: Début de l'importation

        foreach ($rows as $index => $row) {
            $this->rowCount++; // Incrémenter pour le décompte
            Log::debug("Processing row number: " . ($index + 1) . " - Data: " . json_encode($row->toArray())); // LOG: Traitement de la ligne

            $name = trim($row['name'] ?? '');
            $matricule = trim($row['matricule'] ?? '');

            if (empty($name) || empty($matricule)) {
                Log::warning('Skipping row, missing name or matricule: ' . json_encode($row->toArray()));
                continue;
            }

            // Date de naissance
            $dateOfBirth = $row['date_of_birth'] ?? null;
            $formattedDateOfBirth = null;
            if ($dateOfBirth) {
                try {
                    // Tente d'analyser la date, peut-être en ajoutant un log si la date initiale pose problème
                    Log::debug("Attempting to parse date: " . $dateOfBirth);
                    $formattedDateOfBirth = Carbon::parse($dateOfBirth)->format('Y-m-d');
                    Log::debug("Successfully parsed date to: " . $formattedDateOfBirth);
                } catch (\Exception $e) {
                    Log::error("Date parsing error for {$name} (Value: {$dateOfBirth}): " . $e->getMessage()); // LOG: Erreur de parsing de date
                    $formattedDateOfBirth = null;
                }
            }

            // Genre
            $gender = strtoupper(trim($row['gender'] ?? ''));
            if (!in_array($gender, ['M', 'F'])) {
                Log::debug("Gender '{$gender}' for {$name} is invalid, setting to null."); // LOG: Genre invalide
                $gender = null;
            }

            // Classe
            $classeId = $row['classe_id'] ?? null;
            $classe = null;

            if ($classeId) {
                $classe = Classe::find($classeId);
            }

            if (!$classe) {
                Log::warning("Classe ID invalide ou manquant pour {$name} (ID reçu: {$classeId}). Skipping student.");
                continue;
            }

            // Situation
            $situation = $row['situation'] ?? 'R';

            // Récupération de l'année scolaire (si vous décidez de l'utiliser dans la création de l'étudiant)
            $schoolYearId = $this->getSchoolYearId($row);

            Log::debug("Attempting to create student: Name: {$name}, Matricule: {$matricule}, Classe ID: {$classeId}"); // LOG: Avant la création

            // Création de l'étudiant
            try {
                Student::create([
                    'name' => $name,
                    'matricule' => $matricule,
                    'date_of_birth' => $formattedDateOfBirth,
                    'gender' => $gender,
                    'situation' => $situation,
                    'classe_id' => $classeId,
                    // 'school_year_id' => $schoolYearId, // Décommentez si vous ajoutez ce champ à la table 'students'
                ]);

                Log::info("Successfully imported student: {$name} (Matricule: {$matricule})");
            } catch (\Exception $e) {
                Log::error("Database error while creating student {$name}: " . $e->getMessage() . ' - Data: ' . json_encode($row->toArray())); // LOG: Erreur DB
            }
        }

        Log::debug('Finished student import. Total rows processed: ' . $this->rowCount); // LOG: Fin de l'importation
    }

    /**
     * Récupération de l'année scolaire (inchangé)
     */
    private function getSchoolYearId($row)
    {
        // ... (votre code existant)
        $schoolYearValue = $row['annee_scolaire'] ?? $row['school_year'] ?? $row['year'] ?? null;
        if ($schoolYearValue) {
            $schoolYearValue = trim(str_replace('"', '', $schoolYearValue));
        }

        if ($schoolYearValue) {
            $schoolYear = SchoolYear::where('year', $schoolYearValue)->first();
            if ($schoolYear) {
                Log::debug("Found SchoolYear ID: {$schoolYear->id} for value: {$schoolYearValue}");
                return $schoolYear->id;
            }
            Log::warning("SchoolYear not found for value: {$schoolYearValue}");
        }

        $latestSchoolYear = SchoolYear::orderBy('year', 'desc')->first();
        if ($latestSchoolYear) {
            Log::debug("Using latest SchoolYear ID: {$latestSchoolYear->id}");
        }
        return $latestSchoolYear ? $latestSchoolYear->id : null;
    }

    /**
     * Validation des colonnes (inchangé)
     */
    public function rules(): array
    {
        return [
            // ... (vos règles existantes)
            'name' => 'nullable|string|max:255',
            'nom' => 'nullable|string|max:255',
            'matricule' => 'nullable|string|max:50|unique:students,matricule',
            'student_id' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'date_de_naissance' => 'nullable|date',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme,M,F',
            'genre' => 'nullable|string|in:male,female,m,f,masculin,feminin,homme,femme,M,F',
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

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Le nom de l\'étudiant est requis.',
            'matricule.unique' => 'Le matricule :input existe déjà.',
            'date_of_birth.date' => 'La date de naissance doit être une date valide.',
            'gender.in' => 'Le genre doit être masculin ou féminin.',
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
