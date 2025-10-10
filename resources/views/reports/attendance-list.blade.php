<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste d'Appel - {{ $classe->label }}</title>

    <style>
        /* Réduction des marges et taille A4 */
        @page {
            margin: 0.5cm 0.4cm; /* Marges réduites */
            size: A4 portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7.5pt; /* Taille de police globale réduite */
            color: #000;
            margin: 12px;
            padding: 0;
        }

        /* En-tête */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 5px; /* Marge réduite */
        }

        .header-left {
            text-align: left;
            font-size: 8pt; /* Police de l'en-tête réduite */
        }

        .header-center {
            text-align: center;
            flex-grow: 1;
        }

        .header-center .main-title {
            font-size: 9pt; /* Police réduite */
            margin: 0;
            font-weight: normal;
        }

        .header-center .school-name {
            font-size: 9pt; /* Police réduite */
            font-weight: bold;
            margin: 0;
        }

        .header-right {
            text-align: center;
            margin:auto;
            font-size: 8pt; /* Police de l'en-tête réduite */
        }

        /* Tableau */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px; /* Marge réduite */
        }

        .attendance-table th,
        .attendance-table td {
            border: 1px solid #000;
            padding: 1px 2px; /* Padding réduit */
            font-size: 7pt; /* Taille de police du tableau très réduite */
        }

        .attendance-table thead th {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            height: 30px; /* Hauteur d'en-tête réduite */
        }

        .attendance-table tbody td {
            height: 12px; /* Hauteur de ligne très réduite */
        }

        /* Colonnes spécifiques (ajustement des largeurs pour garder le nom lisible) */
        .col-numero {
            width: 3%;
            font-weight: bold;
        }

        .col-matricule {
            width: 8%; /* Légèrement réduite */
            font-size: 6.5pt;
        }

        .col-nom {
            width: 25%; /* Augmentée pour les noms */
            text-align: left;
            /* padding-left: 3px; */
            font-weight: 500;
        }

        .col-naiss {
            width: 6%; /* Réduite */
            font-size: 6.5pt;
        }

        .col-genre {
            width: 3%;
            font-weight: bold;
        }

        .col-red {
            width: 3%;
            font-weight: bold;
            color: #c00;
        }

         .col-separe {
            /* 10 colonnes restantes pour les heures */
            width: 1px; /* Calcul: (100 - (3+8+25+6+3+3)) / 10 = 52 / 10 = 5.2% */
            background-color: #fafafa;
        }

        .col-heure {
            /* 10 colonnes restantes pour les heures */
            width: 1.2%; /* Calcul: (100 - (3+8+25+6+3+3)) / 10 = 52 / 10 = 5.2% */
            background-color: #fafafa;
        }

        /* Lignes spéciales en bas du tableau */
        .footer-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .footer-row .col-nom {
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
            font-size: 8pt; /* Police légèrement augmentée pour les pieds de tableau */
        }

        /* Watermark style */
        .watermark {
            position: fixed;
            bottom: 3px; /* Position ajustée */
            right: 5px; /* Position ajustée */
            font-size: 7pt; /* Taille réduite */
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

    <div class="page-header">
        <div class="header-left">
            Date : __/__/__
        </div>
        <div class="header-center">
            <div class="main-title">Liste d'appel</div>
            <div class="school-name">LYCEE DE BALBALA</div>
        </div>
        <div class="header-right">
            Classe : <strong>{{ $classe->label }}</strong>
        </div>
    </div>

    <table class="attendance-table">
        <thead>
            <tr class="header">
                <th class="col-numero">N°</th>
                <th class="col-matricule">Matricule</th>
                <th class="col-nom">Noms</th>
                <th class="col-naiss">Naiss</th>
                <th class="col-genre">Genre</th>
                <th class="col-red">RED</th>
                <th class="col-heure">7H</th>
                <th class="col-heure">8H</th>
                <th class="col-heure">9H</th>
                <th class="col-heure">10H</th>
                <th class="col-separe"></th>
                <th class="col-heure">11H</th>
                <th class="col-heure">14H</th>
                <th class="col-heure">15H</th>
                <th class="col-heure">16H</th>
                <th class="col-heure">17H</th>
            </tr>
        </thead>
        <tbody>
            @php
                $student_count = count($students);
                $max_rows = $student_count; // Nombre total de lignes souhaité
                $student_count = count($students);
                $empty_rows_needed = $max_rows - $student_count;
            @endphp

            @foreach ($students as $index => $student)
                <tr>
                    <td class="col-numero">{{ $index + 1 }}</td>
                    <td class="col-matricule">{{ $student->matricule ?? '' }}</td>
                    <td class="col-nom">{{ strtoupper($student->name) }}</td>
                    <td class="col-naiss">
                        {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '' }}
                    </td>
                    <td class="col-genre">{{ $student->gender ?? '' }}</td>
                    <td class="col-red">{{ $student->situation==='R' ? 'R' : '' }}</td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-separe"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                </tr>
            @endforeach

            @for ($i = 0; $i < $empty_rows_needed; $i++)
                <tr>
                    <td class="col-numero">{{ $student_count + $i + 1 }}</td>
                    <td class="col-matricule"></td>
                    <td class="col-nom"></td>
                    <td class="col-naiss"></td>
                    <td class="col-genre"></td>
                    <td class="col-red"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                    <td class="col-heure"></td>
                </tr>
            @endfor

            <tr class="footer-row">
                <td class="col-numero"></td>
                <td class="col-matricule"></td>
                <td class="col-nom">Nombre d'absences</td>
                <td class="col-naiss"></td>
                <td class="col-genre"></td>
                <td class="col-red"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
            </tr>
            <tr class="footer-row">
                <td class="col-numero"></td>
                <td class="col-matricule"></td>
                <td class="col-nom">Matière</td>
                <td class="col-naiss"></td>
                <td class="col-genre"></td>
                <td class="col-red"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
            </tr>
            <tr class="footer-row">
                <td class="col-numero"></td>
                <td class="col-matricule"></td>
                <td class="col-nom">Signature</td>
                <td class="col-naiss"></td>
                <td class="col-genre"></td>
                <td class="col-red"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
                <td class="col-heure"></td>
            </tr>
        </tbody>
    </table>

    <div class="watermark">
        Imprimer le {{ date('d-m-Y') }}
    </div>

</body>

</html>
