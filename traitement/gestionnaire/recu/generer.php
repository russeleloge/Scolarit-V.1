<?php

use Dompdf\Dompdf;
use Dompdf\Options;
require('../../../public/connect.php');


http://localhost:5000/traitement/gestionnaire/recu/generer.php
$sql = $pdo->prepare('SELECT * FROM gestionnaire');
$sql->execute();
$users = $sql->fetchAll();


ob_start();
require('./contenu.php');
$html = ob_get_contents();
ob_end_clean();



require('../../../dompdf/autoload.inc.php');

// Police
$options = new Options();
$options->set('defaultFont', 'Courier');

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
// Dimensionnement
$dompdf->setPaper('A4','portrait');

// generer
$dompdf->render();
// Changer le nom du fichier
$fichier = 'reçu.pdf';

$dompdf->setBasePath('./style.css');

// envoi en tant que fichier a telecharger 
$dompdf->stream($fichier);