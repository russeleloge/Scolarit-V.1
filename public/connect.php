<?php

try {
    $strConnection = 'mysql:host=localhost;dbname=gestion_scolarite;charset=utf8';
    $pdo = new PDO($strConnection, 'root', '');
} catch (Exception $e) {
    $msg = 'ERREUR PDO dans' . $e->getMessage();
    die($msg);
}

$req = $pdo->prepare("UPDATE formation AS f SET f.control=1 WHERE f.id_formation IN (SELECT a.id_formation FROM apprenant AS a)");
$req->execute();

$req = $pdo->prepare("UPDATE formation AS f SET f.control=0 WHERE f.id_formation NOT IN (SELECT a.id_formation FROM apprenant AS a)");
$req->execute();

// $req = $pdo->prepare("SELECT t.id_tarif FROM tarif AS t, apprenant AS a, formation AS f WHERE a.debut=t.debut AND a.fin=t.fin AND a.id_formation=f.id_formation AND f.serie=t.serie");
// $req->execute();
// $res_req = $req->fetch();
// $req = $pdo->prepare("UPDATE tarif AS t SET t.control=1 WHERE t.id_tarif IN ('$res_req')");
// $req->execute();

// $req1 = $pdo->prepare("SELECT t.id_tarif FROM tarif AS t, apprenant AS a, formation AS f WHERE a.debut=t.debut AND a.fin=t.fin AND a.id_formation=f.id_formation AND f.serie=t.serie");
// $req1->execute();
// $res_req1 = $req1->fetch();
// $req1 = $pdo->prepare("UPDATE tarif AS t SET t.control=0 WHERE t.id_tarif NOT IN ('$res_req1')");
// $req1->execute();
