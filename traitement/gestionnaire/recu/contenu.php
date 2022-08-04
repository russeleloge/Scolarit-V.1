<?php
session_start();

use Dompdf\Dompdf;
use Dompdf\Options;

http: //localhost:5000/traitement/gestionnaire/recu/contenu.php
require('../../../public/connect.php');

if ($_SESSION['test'] == 'modif_versement') {
    $sql = $pdo->prepare("SELECT `date`,saison,debut,matricule,fin,nomApp,prenom,a.photos,nom,montant,`type`,serie,tranche1,tranche2,tranche3,reste FROM scolarite AS s, apprenant AS a, formation AS f, versement AS v 
     WHERE s.id_apprenant=a.id_apprenant AND v.id_apprenant=a.id_apprenant AND f.id_formation=a.id_formation and v.id_apprenant='" . $_SESSION["id_apprenant"] . "' ");
} elseif ($_SESSION['test'] == 'eff_versement') {
    $sql = $pdo->prepare('SELECT v.id_apprenant,`date`,saison,debut,matricule,fin,nomApp,prenom,a.photos,nom,montant,`type`,serie,tranche1,tranche2,tranche3,reste FROM scolarite AS s, apprenant AS a, formation AS f, versement AS v 
    WHERE s.id_apprenant=a.id_apprenant AND v.id_apprenant=a.id_apprenant AND f.id_formation=a.id_formation ORDER BY id_versement DESC LIMIT 1');
}

// $sql = $pdo->prepare("SELECT * FROM apprenant ORDER BY id_apprenant DESC LIMIT 1");
$sql->execute();
$user = $sql->fetch();
$photo = str_replace(' ', '', $user[8]);
$photo_final = "../../../public/images/$photo";

if ($_SESSION['test'] == 'modif_versement') {
    $req_vue = $pdo->prepare("SELECT * FROM versement WHERE id_apprenant='" . $_SESSION["id_apprenant"] . "' ");
} elseif ($_SESSION['test'] == 'eff_versement') {
    $req_vue = $pdo->prepare("SELECT * FROM versement WHERE id_apprenant=(SELECT id_apprenant FROM versement ORDER BY id_versement DESC LIMIT 1) ORDER BY id_versement DESC");
}

$req_vue->execute();

$debut = $user['debut'];
$fin = $user['fin'];
$serie = $user['serie'];
$tranche1 = $user['tranche1'];
$tranche2 = $user['tranche2'];
$tranche3 = $user['tranche3'];

$req = $pdo->prepare("SELECT * FROM tarif WHERE serie='$serie' AND debut='$debut' AND fin='$fin'");
$req->execute();
$tarif = $req->fetch();
$tranch1 = $tarif['tranche1'];
$tranch2 = $tarif['tranche2'];
$tranch3 = $tarif['tranche3'];

$trch1 = $tranch1 - $tranche1;
$trch2 = $tranch2 - $tranche2;
$trch3 = $tranch3 - $tranche3;
$somPayer = $trch1 + $trch2 + $trch3;
// $info_vers = $req_vue->fetch();

// $date1 = $info_vers['date'];
// $date2 = $info_vers['date'];
// $type = $info_vers['type'];
// $montant = $info_vers['montant'];

$date = [];
$type = [];
$montant = [];
$i = 0;

while ($res = $req_vue->fetch()) {
    //     $containt_pay = 
    //     "<tr class='elt2'>";
    //     "<td class='elt2'>" . $res['date'] . "</td>";
    //     "<td class='elt2'>" . $res['date'] . "</td>";
    //     "<td class='elt2'>" . $res['type'] . "</td>";
    //     "<td class='elt2'>" . $res['montant'] . "</td>";
    //    "</tr>";
    $date[$i] = $res['date'];
    $type[$i] = $res['type'];
    $montant[$i] = $res['montant'];
    $i += 1;
}

// print_r($user);
require('../../../dompdf/autoload.inc.php');
$html = '<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            padding: 10px;
            border-collapse: collapse;
        }

        .table1 {
            margin-bottom: 30px;
            padding: 5px;
        }
        
        .elt1 {
            padding: 5px;          
        }

        .table1 .elt1 {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;     
        }
        
        thead {
            background: black;
            color: white !important;
            padding: 5px; 
        }
        
        table {
            width: 100%;
            color: black;
            font-family: helvetica;
            border-collapse: collapse;
            text-align: center;
            padding: 5px;
        }

        .elt2 {
            padding: 5px;
        }
        
        .table2 .elt2 {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .cls{
            margin-right: 10px;
        }

        h3{
            text-align: center; text-decoration: underline;
        }
    </style>
  <title>Document</title>
</head>

<body>
    <h3 style="">RECU PENSION/FRAIS DIVERS</h3>
    <div style="width: 100%; margin-bottom:40px; margin-top:40px;">
        <div style="width: 69%; display:inline-block; margin-top: 30px; margin-right:30px;">
            <span class="cls">Matricule: </span> <span>' . $user['matricule'] . '</span><br><br>
            <span class="cls">Nom:</span><span>' . $user['nomApp'] . '</span><br><br>
            <span class="cls">Prénom:</span><span>' . $user['prenom'] . '</span><br><br>
            <span class="cls">Spécialité:</span><span>' . $user['nom'] . '</span><br><br>
            <span class="cls">Inscription:</span><span>25000</span><br>
            
        </div>
        <div style="width:25%; height: 175px; display:inline-block;">
            <img src="../../../images/'.$photo_final.'" style="width: 175px; height: 175px;" alt="">
        </div>
    </div>

    <table class="table1">
        <thead>
            <tr class="elt1">
                <th>Date versement</th>
                <th>Date validation</th>
                <th>Type de paiement</th>
                <th>Montant payé</th>
            </tr>
        </thead>
        <tbody>
            <tr class="elt2">
                <td class="elt2">' . $date[0] . '</td>
                <td class="elt2">' . $date[0] . '</td>
                <td class="elt2">' . $type[0] . '</td>
                <td class="elt2">' . $montant[0] . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">' . $date[1] . '</td>
                <td class="elt2">' . $date[1] . '</td>
                <td class="elt2">' . $type[1] . '</td>
                <td class="elt2">' . $montant[1] . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">' . $date[2] . '</td>
                <td class="elt2">' . $date[2] . '</td>
                <td class="elt2">' . $type[2] . '</td>
                <td class="elt2">' . $montant[2] . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">' . $date[3] . '</td>
                <td class="elt2">' . $date[3] . '</td>
                <td class="elt2">' . $type[3] . '</td>
                <td class="elt2">' . $montant[3] . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">' . $date[4] . '</td>
                <td class="elt2">' . $date[4] . '</td>
                <td class="elt2">' . $type[4] . '</td>
                <td class="elt2">' . $montant[4] . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">' . $date[5] . '</td>
                <td class="elt2">' . $date[5] . '</td>
                <td class="elt2">' . $type[5] . '</td>
                <td class="elt2">' . $montant[5] . '</td>
            </tr>
            
        </tbody>
    </table>

    <table class="table2">
        <thead>
            <tr>
                <th class="elt2">Reste à payer par tranche</th>
                <th class="elt2">Tranche 1</th>
                <th class="elt2">Tranche 2</th>
                <th class="elt2">Tranche3</th>
                <th class="elt2">Total payé</th>
            </tr>
        </thead>
        <tbody>
            <tr class="elt2">
                <td class="elt2">Date limite de paiement</td>
                <td class="elt2">' . $tarif['limite1'] . '</td>
                <td class="elt2">' . $tarif['limite2'] . '</td>
                <td class="elt2">' . $tarif['limite3'] . '</td>
                <td class="elt2">' . $somPayer . '</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">Montant dù</td>
                <td class="elt2">' . $tarif['tranche1'] . '</td>
                <td class="elt2">' . $tarif['tranche2'] . '</td>
                <td class="elt2">' . $tarif['tranche3'] . '</td>
                <td class="elt2"></td>
            </tr>
            <tr class="elt2">
                <td class="elt2">Montant payé</td>
                <td class="elt2">' . $trch1 . '</td>
                <td class="elt2">' . $trch2 . '</td>
                <td class="elt2">' . $trch3 . '</td>
                <td class="elt2">Reste à payer total</td>
            </tr>
            <tr class="elt2">
                <td class="elt2">Reste à payer</td>
                <td class="elt2">' . $user['tranche1'] . '</td>
                <td class="elt2">' . $user['tranche2'] . '</td>
                <td class="elt2">' . $user['tranche3'] . '</td>
                <td class="elt2">' . $user['reste'] . '</td>
            </tr>
        </tbody>
    </table>
</body>

</html>';
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('recu.pdf');
