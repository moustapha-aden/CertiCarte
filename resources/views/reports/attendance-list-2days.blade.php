<!DOCTYPE html>

<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Liste d'Appel (2 Jours) - {{ $classe->label }}</title>

<style>
    @page {
        margin: 0.8cm 0.6cm;
        size: A4 landscape; /* Changement au format paysage pour plus de colonnes */
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 8pt; /* Taille réduite pour le paysage */
        color: #000;
        margin: 0;
        padding: 0;
    }

    /* En-tête */
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .header-left, .header-right {
        text-align: left;
        font-size: 9pt;
        width: 30%; /* Pour aligner les dates */
    }

    .header-right {
        text-align: right;
    }

    .header-center {
        text-align: center;
        flex-grow: 1;
        width: 40%;
    }

    .header-center .main-title {
        font-size: 11pt;
        margin: 0;
        font-weight: normal;
    }

    .header-center .school-name {
        font-size: 11pt;
        font-weight: bold;
        margin: 0;
    }

    /* Tableau */
    .attendance-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    .attendance-table th,
    .attendance-table td {
        border: 1px solid #000;
        padding: 2px 3px;
        text-align: center;
        vertical-align: middle;
        font-size: 7.5pt;
    }

    .attendance-table thead th {
        background-color: #d0d0d0;
        font-weight: bold;
        height: 20px;
    }

    .attendance-table tbody td {
        height: 18px;
    }

    /* Colonnes spécifiques (Ajusté pour tenir en Paysage A4) */
    .col-numero { width: 3%; font-weight: bold; }
    .col-matricule { width: 7%; font-size: 7pt; }
    .col-nom {
        width: 17%;
        text-align: left;
        padding-left: 5px;
        font-weight: 500;
        white-space: nowrap;
    }
    .col-naiss { width: 5%; font-size: 7pt; }
    .col-genre { width: 3%; font-weight: bold; }
    .col-red { width: 2%; font-weight: bold; color: #c00; }

    /* Les colonnes des heures sont très fines pour les deux jours (18 colonnes au total) */
    .col-heure-2d {
        width: 3.5%; /* 18 cols * 3.5% = 63% | 3+7+17+5+3+2+63 = 100% */
        background-color: #fafafa;
    }

    /* Entête Jour 1 / Jour 2 */
    .header-day {
        background-color: #b0b0b0;
        font-size: 8.5pt;
    }

    /* Lignes spéciales en bas du tableau */
    .footer-row {
        background-color: #e8e8e8;
        font-weight: bold;
    }

    .footer-row .col-nom {
        font-weight: bold;
        font-size: 8pt;
    }

    /* Watermark style */
    .watermark {
        position: fixed;
        bottom: 8px;
        right: 10px;
        font-size: 8pt;
        color: #888;
        font-style: italic;
    }

    /* Print optimizations */
    @media print {
        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .attendance-table {
            page-break-inside: auto;
        }

        .attendance-table tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    }
</style>

</head>
<body>

<!-- En-tête -->
<div class="page-header">
    <div class="header-left">
        Jour 1 : Date le __/__/____
    </div>
    <div class="header-center">
        <div class="main-title">Liste d'appel (2 Jours)</div>
        <div class="school-name">LYCEE DE BALBALA</div>
    </div>
    <div class="header-right">
        Classe : <strong>{{ $classe->label }}</strong> | Jour 2 : Date le __/__/____
    </div>
</div>

<!-- Tableau des étudiants -->
<table class="attendance-table">
    <thead>
        <tr>
            {{-- Informations Étudiant (Rowspan 2) --}}
            <th class="col-numero" rowspan="2">N°</th>
            <th class="col-matricule" rowspan="2">Matricule</th>
            <th class="col-nom" rowspan="2">Noms</th>
            <th class="col-naiss" rowspan="2">Naiss</th>
            <th class="col-genre" rowspan="2">Genre</th>
            <th class="col-red" rowspan="2">RED</th>

            {{-- Jours (Colspan 9) --}}
            <th class="header-day" colspan="9">JOUR 1</th>
            <th class="header-day" colspan="9">JOUR 2</th>
        </tr>
        <tr>
            {{-- Heures Jour 1 (9 colonnes) --}}
            <th class="col-heure-2d">7H</th>
            <th class="col-heure-2d">8H</th>
            <th class="col-heure-2d">9H</th>
            <th class="col-heure-2d">10H</th>
            <th class="col-heure-2d">11H</th>
            <th class="col-heure-2d">14H</th>
            <th class="col-heure-2d">15H</th>
            <th class="col-heure-2d">16H</th>
            <th class="col-heure-2d">17H</th>

            {{-- Heures Jour 2 (9 colonnes) --}}
            <th class="col-heure-2d">7H</th>
            <th class="col-heure-2d">8H</th>
            <th class="col-heure-2d">9H</th>
            <th class="col-heure-2d">10H</th>
            <th class="col-heure-2d">11H</th>
            <th class="col-heure-2d">14H</th>
            <th class="col-heure-2d">15H</th>
            <th class="col-heure-2d">16H</th>
            <th class="col-heure-2d">17H</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($students as $index => $student)
            <tr>
                <td class="col-numero">{{ $index + 1 }}</td>
                <td class="col-matricule">{{ $student->matricule ?? '' }}</td>
                <td class="col-nom">{{ strtoupper($student->name) }}</td>
                <td class="col-naiss">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '' }}</td>
                <td class="col-genre">{{ $student->gender ?? '' }}</td>
                <td class="col-red">{{ $student->redoublant ? 'R' : '' }}</td>

                {{-- 9 cases vides pour Jour 1 --}}
                @for ($i = 0; $i < 9; $i++)
                    <td class="col-heure-2d"></td>
                @endfor

                {{-- 9 cases vides pour Jour 2 --}}
                @for ($i = 0; $i < 9; $i++)
                    <td class="col-heure-2d"></td>
                @endfor
            </tr>
        @endforeach

        <!-- Lignes de pied de tableau -->
        <tr class="footer-row">
            <td class="col-numero" colspan="6">Nombre d'absences / Nombre d'élèves</td>

            {{-- Ligne d'Absences Jour 1 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>

            {{-- Ligne d'Absences Jour 2 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
        </tr>
        <tr class="footer-row">
            <td class="col-numero" colspan="6">Matière / Enseignant(e)</td>

            {{-- Ligne Matière Jour 1 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>

            {{-- Ligne Matière Jour 2 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
        </tr>
        <tr class="footer-row">
            <td class="col-numero" colspan="6">Signature</td>

            {{-- Ligne Signature Jour 1 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>

            {{-- Ligne Signature Jour 2 (Colspan 9) --}}
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
            <td class="col-heure-2d" ></td>
        </tr>
    </tbody>
</table>

<!-- Watermark -->
<div class="watermark">
    Imprimer le {{ date('d-m-Y') }}
</div>

</body>
</html>
