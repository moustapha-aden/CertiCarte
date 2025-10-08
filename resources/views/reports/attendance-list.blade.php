<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste d'Appel - {{ $classe->label }}</title>

    <style>
        @page {
            margin: 0.8cm 0.6cm;
            size: A4 portrait;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
        }

        /* En-tête */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .header-left {
            text-align: left;
            font-size: 10pt;
        }

        .header-center {
            text-align: center;
            flex-grow: 1;
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

        .header-right {
            text-align: right;
            font-size: 10pt;
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
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
            font-size: 8.5pt;
        }

        .attendance-table thead th {
            background-color: #d0d0d0;
            font-weight: bold;
            height: 22px;
        }

        .attendance-table tbody td {
            height: 20px;
        }

        /* Colonnes spécifiques */
        .col-numero {
            width: 3%;
            font-weight: bold;
        }

        .col-matricule {
            width: 9%;
            font-size: 7.5pt;
        }

        .col-nom {
            width: 22%;
            text-align: left;
            padding-left: 5px;
            font-weight: 500;
        }

        .col-naiss {
            width: 7%;
            font-size: 7.5pt;
        }

        .col-genre {
            width: 4%;
            font-weight: bold;
        }

        .col-red {
            width: 3%;
            font-weight: bold;
            color: #c00;
        }

        .col-heure {
            width: 4.3%;
            background-color: #fafafa;
        }

        /* Lignes spéciales en bas du tableau */
        .footer-row {
            background-color: #e8e8e8;
            font-weight: bold;
        }

        .footer-row .col-nom {
            font-weight: bold;
            font-size: 9pt;
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
            Date : ____________
        </div>
        <div class="header-center">
            <div class="main-title">Liste d'appel</div>
            <div class="school-name">LYCEE DE BALBALA</div>
        </div>
        <div class="header-right">
            Classe : <strong>{{ $classe->label }}</strong>
        </div>
    </div>

    <!-- Tableau des étudiants -->
    <table class="attendance-table">
        <thead>
            <tr>
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
                <th class="col-heure">11H</th>
                <th class="col-heure">14H</th>
                <th class="col-heure">15H</th>
                <th class="col-heure">16H</th>
                <th class="col-heure">17H</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $index => $student)
                <tr>
                    <td class="col-numero">{{ $index + 1 }}</td>
                    <td class="col-matricule">{{ $student->matricule ?? '' }}</td>
                    <td class="col-nom">{{ strtoupper($student->name) }}</td>
                    <td class="col-naiss">
                        {{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : '' }}
                    </td>
                    <td class="col-genre">{{ $student->gender ?? '' }}</td>
                    <td class="col-red">{{ $student->redoublant ? 'R' : '' }}</td>
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
            @endforeach

            <!-- Lignes de pied de tableau -->
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
            </tr>
        </tbody>
    </table>

    <!-- Watermark -->
    <div class="watermark">
        Imprimer le {{ date('d-m-Y') }}
    </div>

</body>

</html>
