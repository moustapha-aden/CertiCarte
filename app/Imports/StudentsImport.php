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
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Carbon\Carbon;

class StudentsImport implements SkipsOnError, SkipsOnFailure, ToCollection, WithHeadingRow, WithValidation, WithCustomCsvSettings
{
    use Importable, SkipsErrors, SkipsFailures;

    private $rowCount = 0;

    /**
     * Paramètres pour CSV (utile si tu importes aussi des CSV)
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',',
            'enclosure' => "\0",
            'input_encoding' => 'UTF-8',
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            try {
                // --- Nom de l'étudiant ---
                $name = trim($row['name'] ?? $row['nom'] ?? '');
                if (empty($name)) continue;

                // --- Matricule ---
                $matricule = trim($row['matricule'] ?? $row['student_id'] ?? '');
                if (empty($matricule)) continue;

                // --- Date de naissance ---
                $dateOfBirth = $row['date_of_birth'] ?? $row['date_de_naissance'] ?? $row['birth_date'] ?? null;
                if ($dateOfBirth) {
                    try {
                        $dateOfBirth = Carbon::parse(trim($dateOfBirth))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $dateOfBirth = null;
                    }
                }

                // --- Genre ---
                $gender = strtolower(trim($row['gender'] ?? $row['genre'] ?? $row['sexe'] ?? ''));
                if (in_array($gender, ['m','masculin','male','homme'])) $gender = 'M';
                elseif (in_array($gender, ['f','feminin','female','femme'])) $gender = 'F';
                else $gender = null;

                // --- Situation ---
                $situation = trim($row['situation'] ?? $row['status'] ?? 'R');

                // --- Classe ---
                $classeId = null;

                // Priorité 1 : utiliser classe_id du fichier Excel si existant
                if (!empty($row['classe_id'])) {
                    $classeId = intval($row['classe_id']);
                    if (!Classe::find($classeId)) {
                        Log::warning("Classe ID {$classeId} non trouvée. L'étudiant sera assigné via nom de classe.");
                        $classeId = null;
                    }
                }

                // Priorité 2 : si pas de classe_id valide, utiliser le nom de la classe
                if (empty($classeId)) {
                    $classeName = trim($row['classe'] ?? $row['class'] ?? $row['class_name'] ?? '');
                    if (!empty($classeName)) {
                        $classe = Classe::firstOrCreate(['label' => $classeName]);
                        $classeId = $classe->id;
                    }
                }

                // Vérification finale avant insertion
                if (empty($classeId)) {
                    throw new \Exception("Impossible d'importer l'étudiant {$name} : classe_id manquant !");
                }

                // --- Création de l'étudiant ---
                Student::create([
                    'name' => $name,
                    'matricule' => $matricule,
                    'date_of_birth' => $dateOfBirth,
                    'gender' => $gender,
                    'situation' => $situation,
                    'classe_id' => $classeId,
                ]);

                $this->rowCount++;
                Log::info("Imported student: {$name} (Matricule: {$matricule})");

            } catch (\Exception $e) {
                Log::error('Error importing student row: '.$e->getMessage());
                Log::error('Row data: '.json_encode($row->toArray()));
            }
        }
    }

    /**
     * Récupération de l'année scolaire (inchangé)
     */
    private function getSchoolYearId($row)
    {
        $schoolYearValue = $row['annee_scolaire'] ?? $row['school_year'] ?? $row['year'] ?? null;
        if ($schoolYearValue) {
             $schoolYearValue = trim(str_replace('"', '', $schoolYearValue));
        }

        if ($schoolYearValue) {
            $schoolYear = SchoolYear::where('year', $schoolYearValue)->first();
            if ($schoolYear) return $schoolYear->id;
        }

        $latestSchoolYear = SchoolYear::orderBy('year', 'desc')->first();
        return $latestSchoolYear ? $latestSchoolYear->id : null;
    }

    /**
     * Validation des colonnes
     */
    public function rules(): array
    {
        return [
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
