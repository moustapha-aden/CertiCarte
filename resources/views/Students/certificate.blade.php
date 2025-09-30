<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat de Scolarité - {{ $student->name }}</title>
    <style>
        /* ---------------------------------------------------- */
        /* 1. DOMPDF FONT FIX (Pour l'affichage de l'Arabe) */
        /* ---------------------------------------------------- */
        @font-face {
            font-family: 'DejaVu Sans';
            /* NOTE: public_path fonctionne UNIQUEMENT dans Laravel, pas dans un navigateur standard */
            /* Assurez-vous que le chemin est correct pour votre installation de DomPDF */
            src: url('{{ public_path("fonts/DejaVuSans.ttf") }}') format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        /* ---------------------------------------------------- */
        /* 2. Styles Généraux */
        /* ---------------------------------------------------- */
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 50px 80px;
            font-size: 14pt;
        }
        .header {
            /* Utilisation de float pour une meilleure compatibilité DomPDF */
            overflow: auto;
            margin-bottom: 50px;
        }
        .header-left, .header-right {
            width: 48%; /* Répartir l'espace */
            line-height: 1.2;
            font-size: 10pt;
        }
        .header-left {
            float: left;
            text-align: left;
        }
        .header-right {
            float: right;
            text-align: right;
        }
        .arabic-header {
            /* Appliquer la police Unicode et le sens de lecture droite à gauche */
            font-family: 'DejaVu Sans', sans-serif;
            direction: rtl;
            text-align: right;
            margin-top: 5px; /* Petit espace entre les blocs */
        }
        .title {
            text-align: center;
            font-size: 18pt;
            font-weight: bold;
            background-color: #e0e0e0;
            padding: 10px 0;
            margin: 40px 0;
            border: 1px solid #ccc;
        }
        .content {
            margin-top: 50px;
            line-height: 1.6; /* Pour une meilleure lisibilité des paragraphes */
        }
        .signature {
            margin-top: 100px;
            text-align: center;
        }
        .proviseur-name {
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="header">
        <div style="text-align:center;">
            <img src="{{ public_path('images/header.png') }}" alt="Header Logo" style="width:100%; max-height:120px;">
        </div>
    </div>


    <!-- DATE -->
    <div style="text-align: right; margin-bottom: 30px; font-size: 12pt;">
        Fait à {{ $lyceeInfo['city'] ?? 'Djibouti' }}, le **{{ $currentDate->translatedFormat('d F Y') }}**
    </div>

    <div class="title">
        CERTIFICAT DE SCOLARITÉ
    </div>

    <div class="content">
        <p>Je soussigné,<span style="font-weight: bold;"> Monsieur MOHAMED HOUSSIEN ALI</span> </p>
        <p>du {{ $lyceeInfo['name'] ?? 'Lycée (Nom Manquant)' }} certifie que l'élève : <span style="font-weight: bold;">{{ $student->name }}</span></p>

        <div style="margin-top: 40px; margin-left: 20px;">
            <p>Né(e) le <span style="font-weight: bold; text-decoration: underline;">{{ optional($student->date_of_birth)->format('d/m/Y') ?? 'Date Inconnue' }}</span>
            à <span style="font-weight: bold; text-decoration: underline;">{{ $student->pays ?? 'Djibouti' }}</span></p>

            <p>est inscrit(e) régulièrement en classe de <span style="font-weight: bold; text-decoration: underline;">{{ $student->classe->label ?? 'Classe Inconnue' }}</span>
            durant l'année scolaire <span style="font-weight: bold; text-decoration: underline;">{{ $school_year}}</span>.</p>
        </div>

        <p style="margin-top: 40px;">
            En foi de quoi, ce certificat est délivré à la demande de l'intéressé(e)
            pour servir et faire valoir ce que de droit.
        </p>
    </div>

    <div class="signature">
        <p>Le Proviseur</p>
        <p class="proviseur-name">MOHAMED HOUSSIEN ALI</p>
    </div>
</body>
</html>
