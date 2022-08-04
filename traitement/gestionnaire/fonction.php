<?php
session_start();
require('../../public/connect.php');
require('../connexion/fonction.php');
$droit_insertion = 1;
// $Object = new DateTime();
// // $Object->setTimezone(new DateTimeZone('Afrique/Douala'));
// $moment = $Object->format("d-m-Y h:i:s a");
$id_gerant = $_SESSION['id_user'];
$Object = new DateTime();
$moment = $Object->format("Y-m-d");

function test_existance($parametre)
{
    global $pdo;
    global $droit_insertion;
    $req = $pdo->prepare("SELECT * FROM apprenant WHERE telephone='$parametre' OR CNI='$parametre' ");
    $req->execute();
    $row = count($req->fetchAll());
    if ($row != 0) {
        $droit_insertion = 0;
    }
}

function fetch_popover()
{
    global $pdo;
    $id = $_POST['id'];
    $output = '';
    $req = $pdo->prepare("SELECT a.id_apprenant,photos,inscription,tranche1,tranche2,tranche3,reste FROM apprenant AS a ,scolarite AS s WHERE a.id_apprenant='$id' AND s.id_apprenant='$id'");
    $req->execute();
    $row = $req->fetch();

    $output = '
        <p> <img src="../../public/images/' . $row['photos'] . '" class="img-responsive img-thumbnail" style="width: 220px; height: 150px;"/></p>
        <p><label class="">Inscription: <label style=" margin-left:50px;">' . $row['inscription'] . '</label></label><p/>
        <p><label>Tranche1: <label style=" margin-left:50px;">' . number_format($row['tranche1'], 0, ',', ' ') . '</label></label><p/>
        <p><label>Tranche2: <label style=" margin-left:50px;">' . number_format($row['tranche2'], 0, ',', ' ') . '</label></label><p/>
        <p><label>Tranche3: <label style=" margin-left:50px;">' . number_format($row['tranche3'], 0, ',', ' ') . '</label></label><p/>
        <p><label>Reste: <label style="color: red; margin-left:70px;">' . number_format($row['reste'], 0, ',', ' ') . '</label></label><p/>
        ';
    echo $output;
}



function insert_student()
{

    global $pdo;
    global $moment;
    global $id_gerant;
    global $droit_insertion;
    $nom = htmlspecialchars(strtoupper($_POST['nom']));
    $prenom = htmlspecialchars(ucwords($_POST['prenom']));
    $sexe = htmlspecialchars($_POST['sexe']);
    $dates = htmlspecialchars($_POST['dates']);
    $tel = htmlspecialchars($_POST['tel']);
    $formation = htmlspecialchars($_POST['formation']);
    $saison = trim(htmlspecialchars($_POST['saison']));
    $debut = trim(htmlspecialchars($_POST['debut']));
    $fin = htmlspecialchars($_POST['fin']);
    $cni = htmlspecialchars($_POST['cni']);
    $control = htmlspecialchars($_POST['control']);
    $mtn_ins = intval(htmlspecialchars($_POST['mtn_ins']));
    $mod_pay = htmlspecialchars($_POST['mod_pay']);
    $matricule = strtoupper(htmlspecialchars($_POST['matricule']));
    $myImage = htmlspecialchars($_POST['mod_pay']);
    $mtn_restant = 0;
    $total_restant = 0;



    test_existance($cni);
    test_existance($tel);
    if ($droit_insertion == 1) {
        $req = $pdo->prepare("SELECT * FROM formation WHERE nom = '$formation'");
        $req->execute();
        $recup_info_formation = $req->fetch();
        $id_formation = $recup_info_formation['id_formation'];
        $serie_formation = $recup_info_formation['serie'];
        // ######################################
        // echo $id_formation;
        // $id_formation = 2;
        $req = $pdo->prepare("INSERT INTO apprenant(id_formation,matricule,saison,debut,fin,nomApp,prenom,sexe,date_naissance,CNI,telephone)
        VALUES('$id_formation','$matricule','$saison','$debut','$fin','$nom','$prenom','$sexe','$dates','$cni','$tel')");
        $req->execute();

        // #################################################
        $req0 = $pdo->prepare("SELECT * FROM apprenant ORDER BY id_apprenant DESC LIMIT 1");
        $req0->execute();
        $recup_info_apprenant = $req0->fetch();
        $id_apprenant = $recup_info_apprenant['id_apprenant'];
        $debut = $recup_info_apprenant['debut'];
        $fin = $recup_info_apprenant['fin'];
        $_SESSION['id_apprenant'] = $id_apprenant;
        // #################################################


        // ############################################
        $req = $pdo->prepare("SELECT * FROM tarif WHERE serie='$serie_formation' AND saison='$saison' AND debut='$debut' AND fin='$fin'");
        $req->execute();
        $recup_info_tarif = $req->fetch();
        $tranche1 = intval($recup_info_tarif['tranche1']);
        $tranche2 = intval($recup_info_tarif['tranche2']);
        $tranche3 = intval($recup_info_tarif['tranche3']);
        $inscription = 25000;
        $total = intval($tranche1) + intval($tranche2) + intval($tranche3);

        // ############################################
        $req = $pdo->prepare("INSERT INTO scolarite(id_apprenant,inscription,tranche1,tranche2,tranche3,reste)
                                VALUES('$id_apprenant','$inscription','$tranche1','$tranche2','$tranche3','$total')");
        $req->execute();
        $_SESSION['test'] = 'eff_versement';

        // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
        // $reqx = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant='$id_apprenant'");
        // $reqx->execute();
        // $recup_info_ap = $reqx->fetch();
        // $trc1 = intval($recup_info_ap['tranche1']);
        // $trc2 = intval($recup_info_ap['tranche2']);
        // $trc3 = intval($recup_info_ap['tranche3']);
        // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$


        // VERSEMENT
        // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
        if (($control % 2) == 0) {
            // Inscription uniquement
            $req = $pdo->prepare("UPDATE scolarite SET inscription=0 WHERE id_apprenant='$id_apprenant'");
            $req->execute();
            $req = $pdo->prepare("INSERT INTO versement(id_gestionnaire,id_apprenant,`type`,montant) VALUES('$id_gerant','$id_apprenant','$mod_pay',25000)");
            $req->execute();
        } else {
            // Inscription et autres
            $req = $pdo->prepare("UPDATE scolarite SET inscription=0 WHERE id_apprenant='$id_apprenant'");
            $req->execute();
            $mtn_restant = intval($mtn_ins) - 25000;

            $req = $pdo->prepare("INSERT INTO versement(id_gestionnaire,id_apprenant,`type`,montant) VALUES('$id_gerant','$id_apprenant','$mod_pay','$mtn_ins')");
            $req->execute();

            // tranche 1
            if ($tranche1 > 0 and $mtn_restant > 0) {
                if ($mtn_restant < $tranche1) {
                    $req = $pdo->prepare("UPDATE scolarite SET tranche1 = tranche1 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                    $mtn_restant = 0;
                } elseif ($mtn_restant >= $tranche1) {
                    $mtn_restant -= $tranche1;
                    $req = $pdo->prepare("UPDATE scolarite SET tranche1=0 WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                }
            }

            // tranche 2
            if ($tranche2 > 0 and $mtn_restant > 0) {
                if ($mtn_restant < $tranche2) {
                    $req = $pdo->prepare("UPDATE scolarite SET tranche2 = tranche2 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                    $mtn_restant = 0;
                } elseif ($mtn_restant >= $tranche2) {
                    $mtn_restant -= intval($tranche2);
                    $req = $pdo->prepare("UPDATE scolarite SET tranche2=0 WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                }
            }

            // tranche 3
            if ($tranche3 > 0 and $mtn_restant > 0) {
                if ($mtn_restant < $tranche3) {
                    $req = $pdo->prepare("UPDATE scolarite SET tranche3 = tranche3 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                    $mtn_restant = 0;
                } elseif ($mtn_restant >= $tranche3) {
                    $mtn_restant -= intval($tranche3);
                    $req = $pdo->prepare("UPDATE scolarite SET tranche3=0 WHERE id_apprenant='$id_apprenant'");
                    $req->execute();
                }
            }

            // Calcul du reste a payer
            $req = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant='$id_apprenant'");
            $req->execute();
            $recup_reste = $req->fetch();
            $tranche1 = $recup_reste['tranche1'];
            $tranche2 = $recup_reste['tranche2'];
            $tranche3 = $recup_reste['tranche3'];
            $reste = intval($tranche1) + intval($tranche2) + intval($tranche3);
            $req = $pdo->prepare("UPDATE scolarite SET reste='$reste' WHERE id_apprenant='$id_apprenant'");
            $req->execute();
        }
        // $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
        echo "true";
    } else {
        echo "false";
    }

    // $img_name = $_FILES['imgrep']['name'];
    // $tmp_name = $_FILES['imgrep']['tmp_name'];

    // $img_upload_path = "./images/".$img_name;
    // move_uploaded_file($tmp_name, $img_upload_path);
}

function search_critere()
{
    global $pdo;
    $count = 0;
    $contain = [];
    $critere = strtoupper($_POST['critere']);
    $req1 = $pdo->prepare("SELECT saison,nomApp,nom,prenom,sexe,date_naissance,CNI,telephone FROM apprenant As ap, formation As f 
                        WHERE ap.id_formation = f.id_formation AND nomApp='$critere' ");
    $req1->execute();
    while ($data = $req1->fetch()) {
        echo $data['nomApp'] . '*' . $data['prenom'] . '*' . $data['sexe'] . '*' . $data['date_naissance'] . '*' . $data['CNI'] . '*' . $data['telephone'] . '*' . $data['nom'] . '*' . $data['saison'];
        // $count += 1;
        // $contain([
        //     'nomApp' => $data['nomApp'],
        //     'prenom' => $data['prenom'],
        //     'sexe' => $data['sexe'],
        //     'naissance' => $data['naissance'],
        //     'CNI' => $data['CNI'],
        //     'tel' => $data['tel'],
        //     'nom' => $data['nom'],
        //     'saison' => $data['saison']
        // ]);
    }
    // echo json_encode($contain);
}
// $_SESSION['id_user']
function select_intervalle()
{
    global $pdo;
    // global $moment;
    $saison = htmlspecialchars($_POST['saison']);
    $formation = htmlspecialchars($_POST['formation']);
    $req = $pdo->prepare("SELECT * FROM formation WHERE nom = '$formation'");
    $req->execute();
    $recup_serie_formation = $req->fetch();
    $serie_formation = $recup_serie_formation['serie'];
    $req = $pdo->prepare("SELECT debut,fin FROM tarif WHERE serie='$serie_formation' AND saison='$saison' ");
    $req->execute();
    while ($row = $req->fetch()) {
        echo $row['debut'] . '*' . $row['fin'] . '*';
    }
}


function eff_vers()
{

    global $pdo;
    global $moment;
    global $id_gerant;
    $id_apprenant = htmlspecialchars($_POST['id_appr']);
    $mtn_verse = htmlspecialchars($_POST['mtn_verse']);
    $mod_pay = htmlspecialchars($_POST['typ_pay']);

    $req = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant='$id_apprenant'");
    $req->execute();
    $row = $req->fetch();
    $tranche1 = intval($row['tranche1']);
    $tranche2 = intval($row['tranche2']);
    $tranche3 = intval($row['tranche3']);
    $rest = intval($row['reste']);
    $mtn_restant = intval($mtn_verse);


    if ($rest <= 0 or $mtn_restant > $rest) {
        echo 'false';
    } else {
        $_SESSION['test'] = 'eff_versement';
        $req = $pdo->prepare("INSERT INTO versement(id_gestionnaire,id_apprenant,`type`,montant) VALUES('$id_gerant','$id_apprenant','$mod_pay','$mtn_verse')");
        $req->execute();

        // tranche 1
        if ($tranche1 > 0 and $mtn_restant > 0) {
            if ($mtn_restant < $tranche1) {
                $req = $pdo->prepare("UPDATE scolarite SET tranche1 = tranche1 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                $req->execute();
                $mtn_restant = 0;
            } elseif ($mtn_restant >= $tranche1) {
                $mtn_restant -= $tranche1;
                $req = $pdo->prepare("UPDATE scolarite SET tranche1=0 WHERE id_apprenant='$id_apprenant'");
                $req->execute();
            }
        }

        // tranche 2
        if ($tranche2 > 0 and $mtn_restant > 0) {
            if ($mtn_restant < $tranche2) {
                $req = $pdo->prepare("UPDATE scolarite SET tranche2 = tranche2 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                $req->execute();
                $mtn_restant = 0;
            } elseif ($mtn_restant >= $tranche2) {
                $mtn_restant -= intval($tranche2);
                $req = $pdo->prepare("UPDATE scolarite SET tranche2=0 WHERE id_apprenant='$id_apprenant'");
                $req->execute();
            }
        }

        // tranche 3
        if ($tranche3 > 0 and $mtn_restant > 0) {
            if ($mtn_restant < $tranche3) {
                $req = $pdo->prepare("UPDATE scolarite SET tranche3 = tranche3 - $mtn_restant, reste = reste - $mtn_restant  WHERE id_apprenant='$id_apprenant'");
                $req->execute();
                $mtn_restant = 0;
            } elseif ($mtn_restant >= $tranche3) {
                $mtn_restant -= intval($tranche3);
                $req = $pdo->prepare("UPDATE scolarite SET tranche3=0 WHERE id_apprenant='$id_apprenant'");
                $req->execute();
            }
        }

        // Calcul du reste a payer
        $req = $pdo->prepare("SELECT * FROM scolarite WHERE id_apprenant='$id_apprenant'");
        $req->execute();
        $recup_reste = $req->fetch();
        $tranche1 = $recup_reste['tranche1'];
        $tranche2 = $recup_reste['tranche2'];
        $tranche3 = $recup_reste['tranche3'];
        $reste = intval($tranche1) + intval($tranche2) + intval($tranche3);
        $req = $pdo->prepare("UPDATE scolarite SET reste='$reste' WHERE id_apprenant='$id_apprenant'");
        $req->execute();

        echo 'true';
    }
}

function change_password(){

    global $pdo;
    global $id_gerant;

    $ancien_mdp = htmlspecialchars($_POST['ancien_mdp']);
    $confirm_mdp = htmlspecialchars($_POST['confirm_mdp']);
    $req = $pdo->prepare("SELECT * FROM gestionnaire WHERE id_gestionnaire='$id_gerant' ");
    $req->execute();
    $recup_mdp = $req->fetch();
    if($recup_mdp['password'] == $ancien_mdp){
        $req = $pdo->prepare("UPDATE gestionnaire SET `password`='$confirm_mdp' WHERE id_gestionnaire='$id_gerant' ");
        $req->execute();
        echo "true";
    }
    else{
        echo "mdp";
    }
}

function active_id_session(){
    $id = htmlspecialchars($_POST['id']);
    $_SESSION['test'] = 'modif_versement';
    $_SESSION["id_apprenant"] = $id;
    echo 'true';
}



