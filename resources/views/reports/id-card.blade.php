<!DOCTYPE html>
<html lang="fr">

<head>
<meta charset="UTF-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Carte d'Étudiant - {{ $student->name }}</title>

<style>

@page{
margin:0;
}

body{
font-family:'Montserrat','Arial',sans-serif;
margin:0;
padding:18mm;
background:#f3f4f6;
}


/* CARTE */

.card-wrapper{

width:86mm;
height:54mm;

border-radius:8mm;
overflow:hidden;

position:relative;

background:#fff url('/images/photo_carte.jpg') center/cover no-repeat;

box-shadow:0 2px 8px rgba(0,0,0,0.12);

}


/* OVERLAY */

.card-wrapper::before{

content:"";
position:absolute;
inset:0;
background:rgba(255,255,255,0.82);

}


/* CONTENU */

.recto-content{

position:relative;
height:100%;
z-index:2;

}


/* LOGO */

.logo-section{

position:absolute;
top:5mm;
left:6mm;

}

.school-logo{

width:14mm;
height:14mm;

}


/* NOM ECOLE */

.school-name{

position:absolute;
top:4mm;
left:0;
width:100%;

text-align:center;

font-size:8.5pt;
font-weight:800;
color:#1d4ed8;

letter-spacing:.6px;

}


/* PHOTO ETUDIANT — AGRANDIE */

.student-photo{

position:absolute;

top:5mm;
right:2mm;

width:24mm;
height:24mm;

border-radius:50%;
border:2px solid #fff;

object-fit:cover;

box-shadow:0 3px 8px rgba(0,0,0,.22);

}


/* BADGE */

.card-badge{

position:absolute;

top:17mm;
right:3mm;
width:100%;

text-align:center;

font-size:10pt;
font-weight:800;

color:#2563eb;

letter-spacing:.5px;

}


/* INFOS */

.student-info{

position:absolute;

top:28mm;
left:3mm;
right:8mm;

font-size:8.5pt;

line-height:1.25;

color:#1e293b;

}

.student-info div{

margin-bottom:0.6mm;

}

.student-info span{

color:#2563eb;
font-weight:700;

}


/* ANNEE */

.school-year{

position:absolute;

right:7mm;
bottom:7mm;

text-align:right;

font-size:6.5pt;
color:#2563eb;

}

.school-year strong{

font-size:9pt;

}


/* SIGNATURE */

.signature{

position:absolute;

bottom:4mm;
left:8mm;

font-size:6.5pt;
color:#2563eb;

}

.signature-line{

width:28mm;
margin-top:1mm;

border-bottom:1px solid #2563eb;

}


</style>

</head>


<body>


<div class="card-wrapper">


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
<span>{{ $student->date_of_birth->format('d/m/Y') }}</span>
</div>


<div>
Classe :
<span>{{ optional($student->classe)->label ?? 'N/A' }}</span>
</div>


</div>



<!-- ANNEE -->

<div class="school-year">

Année scolaire<br>

<strong>

{{ $student->classe->schoolYear->year ?? 'N/A' }}

</strong>

</div>



<!-- SIGNATURE -->

<div class="signature">

Le Proviseur

<div class="signature-line"></div>

</div>



</div>

</div>


</body>

</html>