<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Carte d'Étudiant - {{ $student->name }}</title>
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            margin: 0;
            padding: 20mm;
            font-size: 10pt;
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
            width: 85mm;
            height: 54mm;
            border-radius: 3mm;
            overflow: hidden;
            margin: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
            position: relative;
            display: inline-block;
            vertical-align: top;
            margin-right: 5mm;
        }

        /* === RECTO PROFESSIONNEL === */
        .recto {
            width: 100%;
            height: 100%;
            padding: 5mm;
            position: relative;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
            color: white;
        }

        /* Contenu principal */
        .recto-content {
            height: 100%;
            color: white;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Logo section */
        .logo-section {
            text-align: center;
            margin-bottom: 2mm;
        }

        .school-logo {
            width: 12mm;
            height: 12mm;
            border-radius: 2mm;
            border: 1px solid rgba(255, 255, 255, 0.8);
            background: white;
            padding: 1mm;
        }

        /* En-tête */
        .recto-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.4);
            padding-bottom: 2.5mm;
            margin-bottom: 2mm;
            text-align: center;
        }

        .recto-title {
            font-size: 9pt;
            font-weight: bold;
            line-height: 1.2;
            margin: 0 0 1mm 0;
            letter-spacing: 0.2px;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
        }

        .recto-subtitle {
            font-size: 6.5pt;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin: 0;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
        }

        /* Type de carte */
        .card-type-badge {
            text-align: center;
            font-size: 6.5pt;
            color: white;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            padding: 1.2mm 0;
            margin: 1.5mm 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 2mm;
            border: 1px solid rgba(255, 255, 255, 0.3);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
        }

        /* Section étudiant */
        .student-section {
            margin: 3mm 0;
        }

        .student-layout {
            display: table;
            width: 100%;
        }

        .photo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 22mm;
            padding-right: 3mm;
        }

        .info-cell {
            display: table-cell;
            vertical-align: middle;
        }

        .student-photo {
            width: 18mm;
            height: 18mm;
            border-radius: 2mm;
            border: 2px solid white;
            object-fit: cover;
        }

        .student-name {
            font-size: 8.5pt;
            font-weight: bold;
            line-height: 1.2;
            margin: 0 0 1.5mm 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            color: white;
        }

        .student-detail {
            font-size: 7pt;
            line-height: 1.3;
            margin: 0.8mm 0;
            color: rgba(255, 255, 255, 0.95);
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
        }

        .detail-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            font-weight: 600;
        }

        /* Footer année */
        .year-footer {
            position: absolute;
            bottom: 5mm;
            left: 5mm;
            right: 5mm;
            text-align: center;
            border-top: 1px solid rgba(255, 255, 255, 0.3);
            padding-top: 2mm;
        }

        .year-label {
            font-size: 6pt;
            color: rgba(255, 255, 255, 0.8);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 1mm;
        }

        .year-value {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* === VERSO PROFESSIONNEL === */
        .verso {
            width: 100%;
            height: 100%;
            padding: 5mm;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            position: relative;
            color: #1f2937;
        }

        /* Bande décorative */
        .verso-top-band {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3mm;
            background: linear-gradient(90deg, #1e40af 0%, #3b82f6 50%, #60a5fa 100%);
        }

        /* Badge sécurité */
        .security-badge {
            position: absolute;
            top: 5mm;
            right: 5mm;
            width: 7mm;
            height: 7mm;
            background: rgba(59, 130, 246, 0.1);
            border: 1.5px solid #3b82f6;
            border-radius: 50%;
            text-align: center;
            line-height: 7mm;
            font-size: 9pt;
            color: #1e40af;
            font-weight: bold;
        }

        /* Contenu verso */
        .verso-content {
            margin-top: 5mm;
        }

        .verso-title {
            font-size: 8.5pt;
            font-weight: bold;
            text-align: center;
            margin: 0 0 3mm 0;
            color: #1e40af;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* Lignes d'information */
        .info-grid {
            margin-bottom: 3mm;
        }

        .info-line {
            background: white;
            padding: 1.5mm 2mm;
            margin-bottom: 1.5mm;
            border-radius: 1.5mm;
            border-left: 2.5px solid #3b82f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .info-line-content {
            display: table;
            width: 100%;
        }

        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: 600;
            font-size: 6.5pt;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            vertical-align: middle;
        }

        .info-value {
            display: table-cell;
            width: 65%;
            font-size: 7.5pt;
            color: #111827;
            font-weight: 500;
            vertical-align: middle;
        }

        .info-address {
            margin-top: 2mm;
            padding-top: 2mm;
            border-top: 1px dashed #d1d5db;
        }

        /* Signatures */
        .signatures-section {
            position: absolute;
            bottom: 4mm;
            left: 5mm;
            right: 5mm;
            border-top: 1px solid #e5e7eb;
            padding-top: 2mm;
        }

        .signatures-layout {
            display: table;
            width: 100%;
        }

        .signature-cell {
            display: table-cell;
            width: 48%;
            text-align: center;
            font-size: 6pt;
            color: #6b7280;
        }

        .signature-title {
            font-weight: bold;
            color: #374151;
            font-size: 6.5pt;
            margin-bottom: 1mm;
        }

        .signature-line {
            width: 100%;
            height: 5mm;
            border-bottom: 1px solid #d1d5db;
            margin: 1mm 0;
        }
    </style>
</head>
<body>
    <div class="page">
        <div class="cards-container">

            <!-- RECTO -->
            <div class="card-wrapper">
                <div class="recto">
                    <div class="recto-content">
                        <!-- Logo -->
                        <div class="logo-section">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="Logo" class="school-logo">
                            @else
                                <div class="school-logo" style="display: flex; align-items: center; justify-content: center; font-size: 8pt; font-weight: bold;">
                                    LOGO
                                </div>
                            @endif
                        </div>

                        <!-- En-tête -->
                        <div class="recto-header">
                            <div class="recto-title">{{ $lyceeInfo['name'] }}</div>
                            <div class="recto-subtitle">{{ $lyceeInfo['country'] }}</div>
                        </div>

                        <!-- Type de carte -->
                        <div class="card-type-badge">
                            Carte d'identité scolaire
                        </div>

                        <!-- Section étudiant -->
                        <div class="student-section">
                            <div class="student-layout">
                                <div class="photo-cell">
                                    <img src="{{ $avatar }}" alt="Photo" class="student-photo">
                                </div>
                                <div class="info-cell">
                                    <div class="student-name">{{ $student->name }}</div>
                                    <div class="student-detail">
                                        <span class="detail-label">Matricule:</span> {{ $student->matricule ?? 'N/A' }}
                                    </div>
                                    <div class="student-detail">
                                        <span class="detail-label">Classe:</span> {{ optional($student->classe)->label ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Année scolaire -->
                        <div class="year-footer">
                            <span class="year-label">Année Scolaire</span>
                            <div class="year-value">{{ $school_year }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VERSO -->
            <div class="card-wrapper">
                <div class="verso">
                    <div class="verso-top-band"></div>
                    <div class="security-badge">✓</div>

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
                                    <div class="info-value">{{ $student->gender === 'M' ? 'Masculin' : 'Féminin' }}</div>
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
                                    <div class="info-label">Adresse</div>
                                    <div class="info-value">{{ $student->address ?? 'Non renseignée' }}</div>
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
                            <div class="signature-cell">
                                <div class="signature-title">L'Étudiant(e)</div>
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
