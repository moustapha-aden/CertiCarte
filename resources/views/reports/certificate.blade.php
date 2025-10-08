<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Certificat de Scolarité - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 16pt;
            margin: 50px 80px;
            line-height: 1.6;
        }

        .date {
            text-align: right;
            margin: 40px 0;
            font-size: 12pt;
        }

        .title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            margin: 40px 0;
            text-transform: uppercase;
            border: 1px solid #000;
            background-color: #f0f0f0;
        }

        .content {
            margin-top: 40px;
        }

        .signature {
            margin-top: 100px;
            text-align: center;
            text-transform: capitalize;
        }

        .proviseur-name {
            margin-top: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="text-align:center;">
            <img src="{{ public_path('images/header.png') }}" style="width:100%; max-height:120px;">
        </div>
    </div>


    <!-- DATE -->
    <div class="date">
        Djibouti, le {{ $currentDate->translatedFormat('d F Y') }}
    </div>

    <!-- TITLE -->
    <div class="title">
        Certificat de scolarité
    </div>

    <!-- CONTENT -->
    <div class="content">
        <p>
            Je soussigné, <strong>Mr. MOHAMED HOUSSEIN DIRIEH</strong>,
            proviseur du Lycée Ahmed Farah Ali,
            certifie que l&apos;élève : <strong>{{ $student->name }}</strong>
        </p>

        <p style="margin-top: 40px;">
            Né(e) le <strong>
                {{ optional($student->date_of_birth)->format('d/m/Y') ?? 'Date Inconnue' }}
            </strong>
            à <strong>
                {{ $student->place_of_birth ?? 'Djibouti' }}
            </strong>

            est inscrit(e) régulièrement en classe de
            <strong>
                {{ $student->classe->label ?? 'Classe Inconnue' }}
            </strong>
            durant l&apos;année scolaire
            <strong>
                {{ $school_year }}
            </strong>.
        </p>

        <p style="margin-top: 40px;">
            En foi de quoi, ce certificat est délivré à la demande de l'intéressé(e)
            pour servir et faire valoir ce que de droit.
        </p>
    </div>

    <!-- SIGNATURE -->
    <div class="signature">
        <p>Le Proviseur</p>
        <p class="proviseur-name">MOHAMED HOUSSEIN DIRIEH</p>
    </div>

</body>

</html>
