<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Carte d'Étudiant - {{ $student->name }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Montserrat', 'Arial', sans-serif;
            margin: 0;
            padding: 18mm;
            font-size: 10.5pt;
            background: #f3f4f6;
        }

        .page {
            width: 100%;
        }

        /* Container pour alignement horizontal */
        .cards-container {
            width: 100%;
            margin-bottom: 10mm;
        }

        /* Dimensions carte standard (85.6mm x 53.98mm) */
        .card-wrapper {
            width: 86mm;
            height: 54mm;
            border-radius: 8mm;
            overflow: hidden;
            margin: 12px;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.10);
            position: relative;
            display: inline-block;
            vertical-align: top;
            margin-right: 7mm;
            background: #f7f8fa;
            border: none;
        }

        /* === RECTO PROFESSIONNEL === */
        .recto {
            width: 100%;
            height: 100%;
            padding: 0;
            position: relative;
            background: #fff url('/images/photo_carte.jpg') center center/cover no-repeat;
            color: #222;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .recto::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.75);
            z-index: 1;
        }

        .recto-content,
        .logo-section,
        .badge-d,
        .year-footer {
            position: relative;
            z-index: 2;
        }

        /* Contenu principal */
        .recto-content {
            height: 100%;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Logo section */
        .logo-section {
            position: absolute;
            top: 7mm;
            left: 7mm;
            width: 18mm;
            height: 18mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .school-logo {
            width: 13mm;
            height: 13mm;
            border-radius: 3mm;
            border: none;
            background: #fff;
            padding: 0;
            box-shadow: none;
        }

        /* En-tête */
        .recto-header {
            border-bottom: 1.5px solid rgba(255, 255, 255, 0.25);
            padding-bottom: 2mm;
            margin-bottom: 2mm;
            text-align: left;
        }

        .recto-title {
            font-size: 10pt;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 1mm 0;
            letter-spacing: 0.5px;
            text-shadow: none;
        }

        .recto-subtitle {
            font-size: 7pt;
            color: #dbeafe;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            opacity: 0.7;
            text-shadow: none;
        }

        /* Type de carte */
        .card-type-badge {
            text-align: left;
            font-size: 7pt;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-weight: 700;
            padding: 1.2mm 0 1.2mm 2mm;
            margin: 1.5mm 0;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 2mm;
            border: 1px solid rgba(255, 255, 255, 0.22);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.18);
        }

        /* Section étudiant */
        .student-section {
            margin: 2.5mm 0 0 0;
        }

        .student-layout {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            gap: 2mm;
            width: 100%;
        }

        .photo-cell {
            flex: 0 0 20mm;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 2mm;
        }

        .info-cell {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 1mm;
        }

        .student-photo {
            width: 16mm;
            height: 16mm;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.12);
            background: #e0e7ff;
        }

        .student-name {
            font-size: 12pt;
            font-weight: 700;
            line-height: 1.2;
            margin: 0 0 1mm 0;
            color: #1e293b;
            letter-spacing: 0.7px;
            text-shadow: 0 1px 2px #fff;
        }

        .student-detail {
            font-size: 9pt;
            line-height: 1.3;
            margin: 0.5mm 0;
            color: #2563eb;
            text-shadow: 0 1px 2px #fff;
        }

        .detail-label {
            color: #f59e00;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            font-weight: 700;
        }

        /* Footer année */
        .year-footer {
            position: absolute;
            bottom: 4mm;
            left: 5mm;
            right: 5mm;
            text-align: right;
            border: none;
            padding-top: 1.5mm;
        }

        .year-label {
            font-size: 6.5pt;
            color: #dbeafe;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            display: block;
            margin-bottom: 0.7mm;
        }

        .year-value {
            font-size: 12pt;
            font-weight: 700;
            letter-spacing: 1.2px;
            color: #2563eb;
            text-shadow: none;
        }

        /* === VERSO PROFESSIONNEL === */
        .verso {
            width: 100%;
            height: 100%;
            padding: 0;
            background: #fff;
            position: relative;
            color: #222;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Bande décorative */
        .verso-top-band {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3.5mm;
            display: none;
        }

        /* Badge sécurité */
        .security-badge {
            position: absolute;
            top: 5mm;
            right: 5mm;
            width: 8mm;
            height: 8mm;
            background: rgba(59, 130, 246, 0.08);
            border: 2px solid #2563eb;
            border-radius: 50%;
            text-align: center;
            line-height: 8mm;
            font-size: 10pt;
            color: #2563eb;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(30, 64, 175, 0.10);
        }

        /* Contenu verso */
        .verso-content {
            margin-top: 7mm;
            padding: 0 7mm;
        }

        .verso-title {
            font-size: 9pt;
            font-weight: 700;
            text-align: left;
            margin: 0 0 2.5mm 0;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Lignes d'information */
        .info-grid {
            margin-bottom: 2.5mm;
        }

        .info-line {
            background: transparent;
            padding: 0.5mm 0;
            margin-bottom: 0.5mm;
            border-radius: 0;
            border-left: none;
            box-shadow: none;
            border-bottom: 1px solid #e0e7ff;
            display: flex;
            align-items: center;
        }

        .info-line-content {
            display: flex;
            flex-direction: row;
            align-items: center;
            width: 100%;
        }

        .info-label {
            flex: 0 0 38%;
            font-weight: 600;
            font-size: 7pt;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            vertical-align: middle;
            display: flex;
            align-items: center;
        }

        .info-value {
            flex: 1;
            font-size: 8pt;
            color: #222;
            font-weight: 500;
            vertical-align: middle;
            text-align: left;
        }

        .info-address {
            margin-top: 0.5mm;
            padding-top: 0.5mm;
            border-top: none;
        }

        /* Signatures */
        .signatures-section {
            position: absolute;
            bottom: 4mm;
            left: 5mm;
            right: 5mm;
            border-top: none;
            padding-top: 0;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }

        .signatures-layout {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            width: 100%;
        }

        .signature-cell {
            flex: 1;
            text-align: center;
            font-size: 7pt;
            color: #2563eb;
        }

        .signature-title {
            font-weight: 600;
            color: #2563eb;
            font-size: 7pt;
            margin-bottom: 0.7mm;
        }

        .signature-line {
            width: 100%;
            height: 3mm;
            border-bottom: 1px solid #e0e7ff;
            margin: 0.7mm 0;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="cards-container">

            <!-- RECTO -->
            <div class="card-wrapper">
                <div class="recto">
                    <div class="recto-content" style="position:relative; height:100%; font-family:'Poppins', sans-serif;">

                        <!-- Logo -->
                        <div class="logo-section">
                            @if ($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo" class="school-logo">
                            @else
                                <div class="school-logo"
                                    style="display:flex; align-items:center; justify-content:center; font-size:3pt; font-weight:bold;">
                                    LOGO
                                </div>
                            @endif
                        </div>

                        <!-- Nom du lycée (complet, bien visible et ajusté) -->
                        <div
                            style="
                position:absolute;
                top:5mm;
                left:50%;
                transform:translateX(-50%);
                width:90%;
                text-align:center;
                font-size:8pt;
                font-weight:800;
                color:#1d4ed8;
                text-transform:uppercase;
                letter-spacing:0.6px;
                white-space:nowrap;
                overflow:hidden;
                text-overflow:ellipsis;">
                            Lycée Ahmed Farah Ali
                        </div>

                        <!-- Photo en haut à droite -->
                        <div style="position:absolute; top:7mm; right:6mm;">
                            <img src="{{ $avatar }}" alt="Photo" class="student-photo"
                                style="width:18mm; height:18mm; border-radius:50%; border:1.5px solid #fff; object-fit:cover; box-shadow:0 2px 6px rgba(30,64,175,0.15); background:#e0e7ff;">
                        </div>

                        <!-- Titre de la carte -->
                        <div style="position:absolute; top:15mm; left:0; width:100%; text-align:center;">
                            <span
                                style="font-size:5pt; font-weight:700; color:#2563eb; background:#fff; padding:1.2px 9px; border-radius:8px; box-shadow:0 1px 3px #e0e7ff; letter-spacing:0.8px;">
                                CARTE D'IDENTITÉ SCOLAIRE
                            </span>
                        </div>

                        <!-- Informations à gauche -->
                        <div
                            style="position:absolute; top:28mm; left:8mm; right:8mm; width:auto; text-align:left; line-height:1.5;">
                            <div style="font-size:8pt; font-weight:600; color:#1e293b; margin-bottom:1mm;">
                                Nom : <span style="color:#2563eb; font-weight:700;">{{ $student->name }}</span>
                            </div>
                            <div style="font-size:8pt; font-weight:600; color:#1e293b; margin-bottom:1mm;">
                                Matricule : <span
                                    style="color:#2563eb; font-weight:700;">{{ $student->matricule }}</span>
                            </div>
                            <div style="font-size:8pt; font-weight:600; color:#1e293b;">
                                Classe : <span
                                    style="color:#2563eb; font-weight:700;">{{ optional($student->classe)->label ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <!-- Année scolaire -->
                        <div class="year-footer"
                            style="position:absolute; right:7mm; bottom:7mm; text-align:right; font-size:7pt; color:#2563eb; font-weight:700;">
                            <span
                                style="font-size:5pt; color:#2563eb; text-transform:uppercase; letter-spacing:0.6px; display:block; margin-bottom:0.5mm;">
                                Année scolaire
                            </span>
                            <div style="font-size:7pt; font-weight:700; letter-spacing:0.8px; color:#2563eb;">
                                {{ $student->classe->schoolYear->year ?? 'N/A' }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>


            <!-- VERSO -->
            <div class="card-wrapper">
                <div class="verso">
                    {{-- <div class="verso-top-band"></div>
                    <div class="security-badge">✓</div> --}}

                    <div class="verso-content">
                        <h3 class="verso-title">Informations Personnelles</h3>

                        <div class="info-grid">
                            <div class="info-line">
                                <div class="info-line-content">
                                    <div class="info-label">Né(e) le</div>
                                    <div class="info-value">{{ $student->date_of_birth->format('d/m/Y') }}</div>
                                </div>
                            </div>

                            <div class="info-line">
                                <div class="info-line-content">
                                    <div class="info-label">Sexe</div>
                                    <div class="info-value">{{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}
                                    </div>
                                </div>
                            </div>

                            <div class="info-line">
                                <div class="info-line-content">
                                    <div class="info-label">Délivrée le</div>
                                    <div class="info-value">{{ $currentDate->format('d/m/Y') }}</div>
                                </div>
                            </div>

                            <div class="info-line info-address">
                                <div class="info-line-content">
                                    <div class="info-label">Pays de Naissance</div>
                                    <div class="info-value">{{ $student->place_of_birth ?? 'Non renseignée' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Signatures -->
                    <div class="signatures-section">
                        <div class="signatures-layout">
                            <div class="signature-cell">
                                <div class="signature-title">Le Proviseur</div>
                                <div class="signature-line"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
