<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Cartes d'Étudiants - {{ $classe->label }}</title>

    <style>
        @page {
            margin: 10mm;
            size: A4 portrait;
        }

        body {
            font-family: 'Montserrat', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f3f4f6;
        }

        .page {
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            gap: 5mm;
            padding: 10mm;
        }

        /* CARTE */
        .card-wrapper {
            width: 86mm;
            height: 54mm;
            border-radius: 8mm;
            overflow: hidden;
            position: relative;
            background: #fff ;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            margin-bottom: 5mm;
            margin-right: 5mm;
            page-break-inside: avoid;
        }

        /* Pour avoir 2 cartes par ligne, on enlève la marge de droite toutes les 2 cartes */
        .card-wrapper:nth-child(2n) {
            margin-right: 0;
        }

        /* OVERLAY */
        .card-wrapper::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, 0.82);
            z-index: 1;
        }

        /* CONTENU */
        .recto-content {
            position: relative;
            height: 100%;
            z-index: 2;
        }

        /* LOGO */
        .logo-section {
            position: absolute;
            top: 5mm;
            left: 6mm;
            z-index: 3;
        }

        .school-logo {
            width: 14mm;
            height: 14mm;
            object-fit: contain;
        }

        /* NOM ECOLE */
        .school-name {
            position: absolute;
            top: 4mm;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 8.5pt;
            font-weight: 800;
            color: #1d4ed8;
            letter-spacing: .6px;
            z-index: 3;
        }

        /* PHOTO ETUDIANT — AGRANDIE */
        .student-photo {
            position: absolute;
            top: 5mm;
            right: 2mm;
            width: 24mm;
            height: 24mm;
            border-radius: 50%;
            border: 2px solid #fff;
            object-fit: cover;
            box-shadow: 0 3px 8px rgba(0,0,0,.22);
            z-index: 3;
        }

        /* BADGE */
        .card-badge {
            position: absolute;
            top: 17mm;
            right: 3mm;
            width: 100%;
            text-align: center;
            font-size: 10pt;
            font-weight: 800;
            color: #2563eb;
            letter-spacing: .5px;
            z-index: 3;
        }

        /* INFOS */
        .student-info {
            position: absolute;
            top: 28mm;
            left: 3mm;
            right: 8mm;
            font-size: 8.5pt;
            line-height: 1.25;
            color: #1e293b;
            z-index: 3;
        }

        .student-info div {
            margin-bottom: 0.6mm;
        }

        .student-info span {
            color: #2563eb;
            font-weight: 700;
        }

        /* ANNEE */
        .school-year {
            position: absolute;
            right: 7mm;
            bottom: 7mm;
            text-align: right;
            font-size: 6.5pt;
            color: #2563eb;
            z-index: 3;
        }

        .school-year strong {
            font-size: 9pt;
        }

        /* SIGNATURE */
        .signature {
            position: absolute;
            bottom: 4mm;
            left: 8mm;
            font-size: 6.5pt;
            color: #2563eb;
            z-index: 3;
        }

        .signature-line {
            width: 28mm;
            margin-top: 1mm;
            border-bottom: 1px solid #2563eb;
        }

        @media print {
            body {
                background: white;
            }
            .card-wrapper {
                box-shadow: none;
                border: 1px solid #eee;
            }
        }
    </style>
</head>

<body>
    <div class="page">
        @foreach($students as $student)
            @php
                // Charger la photo réelle de l'étudiant ou générer un avatar
                $avatar = null;
                
                // Essayer de charger la photo depuis le storage
                if ($student->photo && \Illuminate\Support\Facades\Storage::disk('public')->exists($student->photo)) {
                    try {
                        $path = \Illuminate\Support\Facades\Storage::disk('public')->path($student->photo);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $avatar = 'data:image/'.$type.';base64,'.base64_encode($data);
                    } catch (Exception $e) {
                        $avatar = null;
                    }
                }
                
                // Si pas de photo, générer un avatar
                if (!$avatar) {
                    $colors = ['#3b82f6', '#ef4444', '#10b981', '#f59e0b', '#8b5cf6'];
                    $color = $colors[ord(substr($student->name, 0, 1)) % count($colors)];
                    
                    $initial = strtoupper(substr($student->name, 0, 1));
                    
                    $svg = '<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg">
                        <rect width="100" height="100" fill="'.$color.'"/>
                        <text x="50" y="50" font-family="Arial" font-size="40" fill="white" text-anchor="middle" dominant-baseline="middle">'.$initial.'</text>
                    </svg>';
                    
                    $avatar = 'data:image/svg+xml;base64,'.base64_encode($svg);
                }
            @endphp

            <div class="card-wrapper" @if($backgroundImage) style="background: #fff url('{{ $backgroundImage }}') center/cover no-repeat;" @endif>
                <div class="recto-content">
                    <!-- LOGO -->
                    <div class="logo-section">
                        @if ($logoUrl)
                            <img src="{{ $logoUrl }}" class="school-logo">
                        @endif
                    </div>

                    <!-- NOM ECOLE -->
                    <div class="school-name">
                        LYCÉE AHMED FARAH ALI
                    </div>

                    <!-- PHOTO -->
                    <img src="{{ $avatar }}" class="student-photo">

                    <!-- BADGE -->
                    <div class="card-badge">
                        CARTE SCOLAIRE
                    </div>

                    <!-- INFOS -->
                    <div class="student-info">
                        <div>
                            Nom :
                            <span>{{ $student->name }}</span>
                        </div>

                        <div>
                            Matricule :
                            <span>{{ $student->matricule }}</span>
                        </div>

                        <div>
                            Né(e) le :
                            <span>{{ \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') }}</span>
                        </div>

                        <div>
                            Classe :
                            <span>{{ optional($student->classe)->label ?? $classe->label }}</span>
                        </div>
                    </div>

                    <!-- ANNEE -->
                    <div class="school-year">
                        Année scolaire<br>
                        <strong>
                            {{ $student->classe->schoolYear->year ?? $classe->schoolYear->year ?? 'N/A' }}
                        </strong>
                    </div>

                    <!-- SIGNATURE -->
                    <div class="signature">
                        Le Proviseur
                        <div class="signature-line"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>
</html>