<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste d'Appel (2 Jours) - {{ $classe->label }}</title>

    <style>
        /* OPTIMISATION MAXIMALE */
        @page {
            margin: 0.3cm 0.3cm;
            size: A4 portrait;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 7pt;
            color: #000;
        }

        /* En-tête compact */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2mm;
            padding: 1mm 2mm;
        }

        .header-left,
        .header-right {
            font-size: 7pt;
            width: 30%;
        }

        .header-right {
            text-align: right;
        }

        .header-center {
            text-align: right;
            flex-grow: 1;
            width: 40%;
        }

        .header-center .main-title {
            font-size: 9pt;
            margin: 0;
            font-weight: bold;
        }

        .header-center .school-name {
            font-size: 8pt;
            font-weight: bold;
            margin: 2px 0 0 0;
        }

        /* Tableau optimisé */
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .attendance-table th,
        .attendance-table td {
            border: 0.5pt solid #000;
            padding: 0.5mm 0.5mm;
            text-align: center;
            vertical-align: middle;
            font-size: 6pt;
            line-height: 1.1;
            overflow: hidden;
        }

        .attendance-table thead th {
            background-color: #d0d0d0;
            font-weight: bold;
            padding: 1mm 0.5mm;
            font-size: 6pt;
        }

        .attendance-table tbody tr:not(.footer-row) td {
            height: 8mm;
        }

        /* Colonnes spécifiques */
        .col-numero {
            width: 3%;
            font-weight: bold;
            font-size: 6pt;
        }

        .col-matricule {
            width: 6%;
            font-size: 5.5pt;
        }

        .col-nom {
            width: 18%;
            text-align: left;
            padding-left: 2mm !important;
            font-weight: 500;
            font-size: 6pt;
        }

        .col-naiss {
            width: 6%;
            font-size: 5.5pt;
        }

        .col-genre {
            width: 3%;
            font-weight: bold;
        }

        .col-red {
            width: 2.5%;
            font-weight: bold;
            color: #c00;
        }

        /* Colonnes des heures */
        .col-heure-2d {
            width: 1.75%;
            background-color: #fafafa;
        }

        /* En-tête Jour 1 / Jour 2 */
        .header-day {
            background-color: #b0b0b0;
            font-size: 7pt;
            font-weight: bold;
        }

        /* Lignes footer */
        .footer-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .footer-row td {
            height: 8mm !important;
        }

        .footer-row .col-nom {
            text-align: left;
            padding-left: 3mm !important;
            font-weight: bold;
            font-size: 6.5pt;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            bottom: 3mm;
            right: 3mm;
            font-size: 5pt;
            color: #888;
            font-style: italic;
        }

        /* Optimisation impression */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .attendance-table {
                page-break-inside: avoid;
            }

            @page {
                margin: 0.3cm;
            }
        }
    </style>

</head>

<body>

    <div class="page-header">
        <div class="header-left">
            Jour 1 : Date le __/__/____
            <br>
            <br>
            Jour 2 : Date le __/__/____
        </div>
        <div class="header-center">
            <div class="school-name">LYCEE AHMED FARAH ALI</div>
        </div>
        <div class="header-right">
            Classe : <strong>{{ $classe->label }}</strong><br>
        </div>
    </div>

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
            @php
                $max_rows = 60; // Maximum 60 élèves
                // Limiter à 60 élèves maximum
                $students_to_display = $students->take($max_rows);
            @endphp

            @foreach ($students_to_display as $index => $student)
                <tr>
                    <td class="col-numero">{{ $index + 1 }}</td>
                    <td class="col-matricule">{{ $student->matricule ?? '' }}</td>
                    <td class="col-nom">{{ strtoupper($student->name) }}</td>
                    <td class="col-naiss">
                        {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '' }}
                    </td>
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

            @php
                // Nombre de colonnes de données (heures) = 9 (J1) + 9 (J2) = 18
                $cols_data = 18;
                // Nombre de colonnes d'infos étudiants fusionnées = 6
                $cols_info = 6;
            @endphp

            {{-- 3 lignes footer --}}
            <tr class="footer-row">
                <td class="col-nom" colspan="{{ $cols_info }}">Nombre d'absences / Nombre d'élèves</td>
                @for ($i = 0; $i < $cols_data; $i++)
                    <td class="col-heure-2d"></td>
                @endfor
            </tr>
            <tr class="footer-row">
                <td class="col-nom" colspan="{{ $cols_info }}">Matière / Enseignant(e)</td>
                @for ($i = 0; $i < $cols_data; $i++)
                    <td class="col-heure-2d"></td>
                @endfor
            </tr>
            <tr class="footer-row">
                <td class="col-nom" colspan="{{ $cols_info }}">Signature</td>
                @for ($i = 0; $i < $cols_data; $i++)
                    <td class="col-heure-2d"></td>
                @endfor
            </tr>
        </tbody>
    </table>

    <div class="watermark">
        Imprimer le {{ date('d-m-Y') }}
    </div>

</body>

</html>
