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
// Correction ici : Utilisation de la classe Failure du namespace Validators
use Maatwebsite\Excel\Validators\Failure;

/**
 * Classe d'importation pour les étudiants.
 *
 * Elle est modifiée pour :
 * 1. Utiliser le 'label' de la classe au lieu de 'classe_id'.
 * 2. Utiliser la colonne 'annee_scolaire' pour grouper les classes.
 * 3. Créer automatiquement l'année scolaire et la classe si elles n'existent pas (logique firstOrCreate).
 */
class StudentsImport implements SkipsOnError, SkipsOnFailure, ToCollection, WithCustomCsvSettings, WithHeadingRow, WithValidation
{
    use Importable, SkipsErrors, SkipsFailures;

    private $rowCount = 0;

    /**
     * IMPLÉMENTATION CRUCIALE : Log l'échec de validation
     * Cette méthode capture les lignes qui n'ont pas atteint collection()
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $rowNumber = $failure->row();
            $errors = json_encode($failure->errors());
            $values = json_encode($failure->values());

            // LOG: Enregistre chaque ligne qui a échoué à la validation et la raison
            Log::warning("Validation failure detected - Row: {$rowNumber}. Errors: {$errors}. Values: {$values}");
        }
    }

    /**
     * Paramètres pour CSV
     */
    public function getCsvSettings(): array
    {
        Log::debug('CSV Settings loaded.');
        return [
            'delimiter' => ',',
            'enclosure' => "\0",
            'input_encoding' => 'UTF-8',
        ];
    }

    public function collection(Collection $rows)
    {
        Log::debug('Starting student import. Total rows to process (after validation): ' . $rows->count());

        foreach ($rows as $index => $row) {
            $this->rowCount++;
            $rowId = $row['id'] ?? ($index + 2);
            Log::debug("Processing row ID: {$rowId} - Data: " . json_encode($row->toArray()));

            $name = trim($row['name'] ?? '');
            $matricule = trim($row['matricule'] ?? '');

            if (empty($name) || empty($matricule)) {
                Log::warning('Skipping row, missing name or matricule: ' . json_encode($row->toArray()));
                continue;
            }

            // 1. GESTION DE L'ANNÉE SCOLAIRE (MODIFIÉ : CRÉATION SI INEXISTANTE)
            $schoolYearId = $this->getSchoolYearId($row);

            if (!$schoolYearId) {
                 Log::error("Skipping student {$name}: School Year ID could not be determined or created.");
                 continue;
            }

            // 2. GESTION DU LABEL DE CLASSE (MODIFIÉ : CRÉATION SI INEXISTANTE DANS L'ANNÉE DONNÉE)
            $classeLabel = trim($row['classe'] ?? $row['label_classe'] ?? '');

            if (empty($classeLabel)) {
                 Log::warning("Skipping student {$name}: Class label is missing.");
                 continue;
            }

            // Recherche ou Création de la Classe basée sur le label ET l'année scolaire
            // Utilisation de firstOrCreate pour créer la classe si elle n'existe pas
            try {
                $classe = Classe::firstOrCreate(
                    [
                        'label' => $classeLabel,
                        'year_id' => $schoolYearId
                    ],
                    [
                        'label' => $classeLabel,
                        'year_id' => $schoolYearId
                    ]
                );

                $classeId = $classe->id;
                Log::debug("Classe processed: ID {$classeId} for Label '{$classeLabel}' in Year ID {$schoolYearId}.");

            } catch (\Exception $e) {
                Log::error("Failed to find or create Classe {$classeLabel} for year {$schoolYearId}: " . $e->getMessage());
                continue;
            }

            // Date de naissance (pas de changement)
            $dateOfBirth = $row['date_of_birth'] ?? null;
            $formattedDateOfBirth = null;
            if ($dateOfBirth) {
                try {
                    // Utilisation de createFromFormat pour gérer le format 'DD/MM/YYYY' de ton CSV
                    $date = Carbon::createFromFormat('d/m/Y', $dateOfBirth);
                    $formattedDateOfBirth = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    try {
                        $formattedDateOfBirth = Carbon::parse($dateOfBirth)->format('Y-m-d');
                    } catch (\Exception $e2) {
                        Log::error("Date parsing error for {$name} (Value: {$dateOfBirth}): " . $e2->getMessage());
                        $formattedDateOfBirth = null;
                    }
                }
            }

            // Genre (pas de changement)
            $gender = strtoupper(trim($row['gender'] ?? ''));
            if (!in_array($gender, ['M', 'F'])) {
                $gender = null;
            }

            // Situation (pas de changement)
            $situation = strtoupper(trim($row['situation'] ?? 'R'));
            $allowedSituations = ['R', 'NR'];
            if (!in_array($situation, $allowedSituations)) {
                $situation = 'R'; // Valeur par défaut
            }

            Log::debug("Attempting to create student: Name: {$name}, Matricule: {$matricule}, Classe ID: {$classeId}");

            // Création de l'étudiant
            try {
                Student::create([
                    'name' => $name,
                    'matricule' => $matricule,
                    'date_of_birth' => $formattedDateOfBirth,
                    'gender' => $gender,
                    'pays' => $row['pays'] ?? null,
                    'situation' => $situation,
                    'classe_id' => $classeId, // Utilisation de l'ID trouvé ou créé
                    'school_year_id' => $schoolYearId,
                ]);

                Log::info("Successfully imported student: {$name} (Matricule: {$matricule})");
            } catch (\Exception $e) {
                Log::error("Database error while creating student {$name}: " . $e->getMessage() . ' - Data: ' . json_encode($row->toArray()));
            }
        }

        Log::debug('Finished student import. Total rows processed (that passed validation): ' . $this->rowCount);
    }

    /**
     * Récupération ou Création de l'année scolaire.
     */
    private function getSchoolYearId($row): ?int
    {
        // On cherche 'annee_scolaire', 'school_year', ou 'year' dans le fichier.
        $schoolYearValue = $row['annee_scolaire'] ?? $row['school_year'] ?? $row['year'] ?? null;
        if ($schoolYearValue) {
            $schoolYearValue = trim(str_replace('"', '', $schoolYearValue));

            // Si la valeur est présente, on tente de la trouver ou de la créer
            if (!empty($schoolYearValue)) {
                $schoolYear = SchoolYear::firstOrCreate(
                    ['year' => $schoolYearValue],
                    ['year' => $schoolYearValue]
                );

                Log::debug("SchoolYear processed: ID {$schoolYear->id} for value: {$schoolYearValue}");
                return $schoolYear->id;
            }
        }

        // Si l'année est absente dans le fichier, on prend la plus récente comme valeur de secours
        $latestSchoolYear = SchoolYear::orderBy('year', 'desc')->first();
        if ($latestSchoolYear) {
            Log::debug("Using latest SchoolYear ID: {$latestSchoolYear->id} as fallback.");
            return $latestSchoolYear->id;
        }

        // Si aucune année n'est trouvée (même pas en fallback)
        Log::error("Cannot determine or create a SchoolYear.");
        return null;
    }

    /**
     * Helper pour afficher la valeur de l'année scolaire.
     */
    private function getSchoolYearValue(int $id): ?string
    {
         $year = SchoolYear::find($id);
         return $year ? $year->year : 'N/A';
    }

    /**
     * Validation des colonnes (pas de changement dans les règles)
     */
    public function rules(): array
    {
        return [
            // Colonne dans le fichier Excel (label de la classe)
            'classe' => 'required|string|max:255',
            // Colonne dans le fichier Excel (année scolaire : ex. 2024-2025)
            'annee_scolaire' => 'nullable|string|max:20', // Si non spécifié, le fallback sera utilisé.
            'name' => 'required|string|max:255', // Rendu 'required' pour garantir l'utilité de la ligne
            'matricule' => 'required|string|max:50|unique:students,matricule', // Rendu 'required'
            'date_of_birth' => 'nullable',
            'gender' => 'nullable|string',
            'pays' => 'nullable|string|max:255',
            'situation' => 'nullable|string|max:2',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'matricule.unique' => 'Le matricule :input existe déjà.',
            'name.required' => 'Le nom de l\'étudiant est requis.',
            'matricule.required' => 'Le matricule de l\'étudiant est requis.',
            'classe.required' => 'Le nom de la classe est requis (colonne "classe").',
            // NOTE: Les messages d'erreur pour les classes inexistantes sont gérés
            // dans la méthode `collection()` car ils dépendent de deux colonnes.
        ];
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }
}
