<?php

namespace App\Exports;

use App\Models\StudentImport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentImportErrorsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $studentImport;

    public function __construct(StudentImport $studentImport)
    {
        $this->studentImport = $studentImport;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->studentImport->errors()->orderBy('row_number')->get();
    }

    public function headings(): array
    {
        return [
            'Nom',
            'Matricule',
            'Date de Naissance',
            'Pays de Naissance',
            'Situation',
            'Genre',
            'Classe',
            'Année Scolaire',
        ];
    }

    /**
     * @param  mixed  $error
     */
    public function map($error): array
    {
        $rowData = $error->row_data ?? [];

        return [
            $rowData['name'] ?? '',
            $rowData['matricule'] ?? '',
            $rowData['date_of_birth'] ?? '',
            $rowData['place_of_birth'] ?? '',
            $rowData['situation'] ?? '',
            $rowData['gender'] ?? '',
            $rowData['classe'] ?? '',
            $rowData['annee_scolaire'] ?? '',
        ];
    }

    /**
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E5E7EB']]],
        ];
    }
}
