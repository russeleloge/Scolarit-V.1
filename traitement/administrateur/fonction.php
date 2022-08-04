<?php
session_start();
require("../../public/connect.php");
$droit_insertion = 1;
$tableau_sms = [];
$i = 0;

function test_existance($parametre)
{
    global $pdo;
    global $droit_insertion;
    global $tableau_sms;
    global $i;
    $req = $pdo->prepare("SELECT * FROM gestionnaire WHERE username='$parametre' OR telephone1='$parametre' OR CNI='$parametre' ");
    $req->execute();
    $row = count($req->fetchAll());
    if ($row != 0) {
        $droit_insertion = 0;
        $tableau_sms[$i] = $parametre;
    }
    $i += 1;
}

function add_user()
{
    global $pdo;
    global $droit_insertion;
    global $tableau_sms;
    global $i;
    $nom = htmlspecialchars(strtoupper($_POST['nom']));
    $prenom = htmlspecialchars(ucwords($_POST['prenom']));
    $sexe = htmlspecialchars($_POST['sexe']);
    $dates = htmlspecialchars($_POST['dates']);
    $tel1 = htmlspecialchars($_POST['tel1']);
    $tel2 = htmlspecialchars($_POST['tel2']);
    $cni = htmlspecialchars($_POST['cni']);
    $username = htmlspecialchars($_POST['username']);
    $password = "password";
    // password_hash("password", PASSWORD_DEFAULT);
    if ($tel2 == '') {
        $tel2 = 0;
    }

    test_existance($username);
    test_existance($tel1);
    test_existance($cni);


    if ($droit_insertion == 1) {
        $req = $pdo->prepare("INSERT INTO gestionnaire(username,`password`,nom,prenomgest,sexe,date_naissance,telephone1,telephone2,CNI,privilege,autorisation)
            VALUES('$username','$password','$nom','$prenom','$sexe','$dates','$tel1','$tel2','$cni','sécrétaire','oui')");
        $req->execute();
        $tableau_sms[$i] = "true";
        echo 'true';
    } else {
        $tableau_sms[$i] = "false";
        echo 'false';
    }
    // echo count($tableau_sms);
    // for ($k = 0; $k < count($tableau_sms); $k++) {
    //     echo $tableau_sms[$k];
    // }

}


function add_formation()
{

    global $pdo;
    $serie = htmlspecialchars(ucfirst($_POST['serie']));
    $nom = htmlspecialchars(ucfirst($_POST['nom']));
    $req = $pdo->prepare("SELECT * FROM formation WHERE nom='$nom'");
    $req->execute();
    $row = count($req->fetchAll());
    if ($row != 0) {
    } else {
        $req = $pdo->prepare("INSERT INTO formation(nom,serie) VALUES('$nom','$serie')");
        $req->execute();
        echo 'true';
        $_SESSION['table'] = 'formation';
    }
}

function update_formation()
{

    global $pdo;
    $id = $_POST['id'];
    $serie = htmlspecialchars(ucfirst($_POST['serie']));
    $nom = htmlspecialchars(ucfirst($_POST['nom']));
    $req = $pdo->prepare("UPDATE formation SET nom='$nom', serie='$serie' WHERE id_formation='$id'");
    $req->execute();

    echo "true";
}


function add_tarif()
{
    global $pdo;
    $serie = htmlspecialchars(ucfirst($_POST['serie']));
    $saison = htmlspecialchars(ucfirst($_POST['saison']));
    $debut = htmlspecialchars($_POST['debut']);
    $fin = htmlspecialchars($_POST['fin']);
    $tranche1 = htmlspecialchars($_POST['tranche1']);
    $tranche2 = htmlspecialchars($_POST['tranche2']);
    $tranche3 = htmlspecialchars($_POST['tranche3']);
    $date1 = htmlspecialchars($_POST['date1']);
    $date2 = htmlspecialchars($_POST['date2']);
    $date3 = htmlspecialchars($_POST['date3']);
    $total = htmlspecialchars($_POST['total']);

    $req = $pdo->prepare("SELECT * FROM tarif WHERE serie='$serie' AND saison='$saison' AND debut='$debut' AND fin='$fin'");
    $req->execute();
    $row = count($req->fetchAll());
    if ($row != 0) {
        echo "duplication";
    } else {
        $req = $pdo->prepare("INSERT INTO tarif(serie,saison,debut,fin,tranche1,tranche2,tranche3,limite1,limite2,limite3,total) VALUES('$serie','$saison','$debut','$fin','$tranche1','$tranche2','$tranche3','$date1','$date2','$date3','$total')");
        $req->execute();
        echo ("true");
        $_SESSION['table'] = 'tarif';
    }
}

function change_autorisation()
{
    global $pdo;
    $recup_id = htmlspecialchars($_POST['recup_id']);
    $control = htmlspecialchars($_POST['control']);
    $req = $pdo->prepare("UPDATE gestionnaire SET autorisation='$control' WHERE id_gestionnaire='$recup_id'");
    $req->execute();
}

function reset_password()
{
    global $pdo;
    $recup_id = htmlspecialchars($_POST['recup_id']);
    $password = 'password';
    $req = $pdo->prepare("UPDATE gestionnaire SET `password`='$password' WHERE id_gestionnaire='$recup_id'");
    $req->execute();
    echo 'true';
}

function select_gest()
{
    global $pdo;
    $req = $pdo->prepare("SELECT * FROM gestionnaire");
    $req->execute();
    while ($row = $req->fetch()) {
        echo '*' . $row['nom'] . ' - ' . $row['prenomgest'] . '*';
    }
}

function select_formation()
{
    global $pdo;
    $req = $pdo->prepare("SELECT * FROM formation WHERE `control`=1");
    $req->execute();
    while ($row = $req->fetch()) {
        echo $row['nom'] . '*';
    }
}

function delete_formation()
{
    global $pdo;
    $recup_id = htmlspecialchars($_POST['recup_id']);
    $req = $pdo->prepare("DELETE FROM formation WHERE id_formation='$recup_id'");
    $req->execute();
    echo 'true';
}

function modif_mtn_verse()
{

    global $pdo;
    $id_vers = htmlspecialchars($_POST['id_vers']);
    $mtn_verse = htmlspecialchars($_POST['mtn_verse']);
    $typ_pay = htmlspecialchars($_POST['typ_pay']);


    //Recuperer les tranches de scolariter de l'etudiant concerner 
    $req = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant=(
        SELECT id_apprenant FROM versement WHERE id_versement='$id_vers' 
    )");
    $req->execute();
    $row = $req->fetch();
    $tranche1 = $row['tranche1'];
    $tranche2 = $row['tranche2'];
    $tranche3 = $row['tranche3'];
    $reste = $row['reste'];

    $req = $pdo->prepare("SELECT * FROM versement WHERE id_versement='$id_vers'");
    $req->execute();
    $row = $req->fetch();
    $ancien_mtn = $row['montant'];

    $req = $pdo->prepare("SELECT a.saison,a.debut,a.fin,f.serie FROM apprenant AS a, formation AS f WHERE a.id_formation=f.id_formation
                            AND a.id_apprenant=(SELECT id_apprenant FROM versement WHERE id_versement='$id_vers')");
    $req->execute();
    $row = $req->fetch();

    $req = $pdo->prepare("SELECT * FROM tarif WHERE serie='$row[3]' AND saison='$row[0]' AND debut='$row[1]' AND fin='$row[2]'");
    $req->execute();
    $row = $req->fetch();
    $tr1 = $row['tranche1'];
    $tr2 = $row['tranche2'];
    $tr3 = $row['tranche3'];
    // echo $tr3;

    $req = $pdo->prepare("SELECT * FROM versement WHERE id_versement='$id_vers'");
    $req->execute();
    $row = $req->fetch();
    $id_appr = $row['id_apprenant'];

    $difference = $ancien_mtn - $mtn_verse;
    $indice = 0;

    if ($tranche3 < $tr3) {
        if (($tranche3 + $difference) >= $tr3) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche3='$tr3' WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr3 - $tranche3);
        } elseif (($tranche3 + $difference) < $tr3) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche3=tranche3 + $difference WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr3 - $tranche3);
        }
    }

    if ($tranche2 < $tr2 and $difference > 0) {
        if (($tranche2 + $difference) >= $tr2) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche2='$tr2' WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr2 - $tranche2);
        } elseif (($tranche2 + $difference) < $tr2) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche2=tranche2 + $difference WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr2 - $tranche2);
        }
    }

    if ($tranche1 >= 0 and $tranche1 < $tr1 and $difference > 0) {
        if (($tranche1 + $difference) >= $tr1) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche1='$tr1' WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr1 - $tranche1);
        } elseif (($tranche1 + $difference) < $tr1) {
            $req = $pdo->prepare("UPDATE scolarite SET tranche1=tranche1 + $difference WHERE id_apprenant='$id_appr'");
            $req->execute();
            $difference -= ($tr1 - $tranche1);
        }
    }


    // Calcul du reste a payer
    $req = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant='$id_appr'");
    $req->execute();
    $recup_reste = $req->fetch();
    $tranche1 = $recup_reste['tranche1'];
    $tranche2 = $recup_reste['tranche2'];
    $tranche3 = $recup_reste['tranche3'];
    $reste = intval($tranche1) + intval($tranche2) + intval($tranche3);
    $req = $pdo->prepare("UPDATE scolarite SET reste='$reste' WHERE id_apprenant='$id_appr'");
    $req->execute();

    // Changer le montant du versement
    $req = $pdo->prepare("UPDATE versement SET montant='$mtn_verse' WHERE id_versement='$id_vers'");
    $req->execute();

    $_SESSION['test'] = 'modif_versement';
    $_SESSION["id_apprenant"] = $id_appr;

    // Modifier l'ancien montant verser


    // Modifier la scolariter de l'apprenant concerner
    

}

function session_formation()
{
    $_SESSION['table'] = 'formation';
}

function session_gestionnaire()
{
    $_SESSION['table'] = 'gestionnaire';
}

function session_tarif()
{
    $_SESSION['table'] = 'tarif';
}


