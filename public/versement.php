<?php
session_start();
if ($_SESSION['autoriser'] != "oui") {
    header(('Location: ../index.html'));
    exit();
}
require("./connect.php");
// tester d'abord les conditions
if (isset($_POST['envoi'])) {
    $critere = $_POST['critere'];
    $selection = explode('-', $_POST['selection']);
    $nom = str_replace(' ', '', $selection[0]);
    $prenom = str_replace(' ', '', $selection[1]);
    $solvabilite = $_POST['selection3'];
    $formation = $_POST['selection2'];
    $tranche = $_POST['tranche'];
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $timestamp1 = strtotime($date1);
    $timestamp2 = strtotime($date2);
    $moment1 = date('Y-m-d h:i:s', $timestamp1);
    $moment2 = date('Y-m-d h:i:s', $timestamp2);
    global $ligne;



    if ($critere == 'Gestionnaire') {
        $req = $pdo->prepare("SELECT nomApp,a.prenom,photos,nom,g.prenomgest,montant,id_versement,v.type,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v
        WHERE g.nom='$nom' AND g.prenomgest='$prenom' AND a.id_apprenant=v.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND `date` BETWEEN '$moment1' AND '$moment2'");
        $req->execute();

        $req1 = $pdo->prepare("SELECT SUM(montant) FROM apprenant AS a, gestionnaire AS g, versement AS v
        WHERE g.nom='$nom' AND g.prenomgest='$prenom' AND a.id_apprenant=v.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND `date` BETWEEN '$moment1' AND '$moment2'");
        $req1->execute();
        $ligne = $req1->fetch();
    } elseif ($critere == 'Periode') {
        $req = $pdo->prepare("SELECT nomApp,a.prenom,photos,nom,g.prenomgest,montant,id_versement,v.type,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v
        WHERE a.id_apprenant=v.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND `date` BETWEEN '$moment1' AND '$moment2'");
        $req->execute();

        $req1 = $pdo->prepare("SELECT SUM(montant) FROM apprenant AS a, gestionnaire AS g, versement AS v
        WHERE a.id_apprenant=v.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND `date` BETWEEN '$moment1' AND '$moment2'");
        $req1->execute();
        $ligne = $req1->fetch();
    } elseif ($critere == 'Scolarité') {
        if ($solvabilite == 'Payé') {
            if ($tranche == 'tranche1') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche1=0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
            if ($tranche == 'tranche2') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche2=0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
            if ($tranche == 'tranche3') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche3=0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
        } elseif ($solvabilite == 'Non payé') {
            if ($tranche == 'tranche1') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche1>0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
            if ($tranche == 'tranche2') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche2>0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
            if ($tranche == 'tranche3') {
                $req = $pdo->prepare("SELECT nomApp,a.prenom,g.nom,g.prenomgest,montant,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v, formation AS f, scolarite AS s
                WHERE f.nom='$formation' AND s.tranche3>0 AND a.id_apprenant=v.id_apprenant AND a.id_apprenant = s.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire AND a.id_formation=f.id_formation ");
                $req->execute();
            }
        }
    }
} else {
    $req = $pdo->prepare("SELECT nomApp,a.prenom,photos,nom,g.prenomgest,montant,id_versement,v.type,`date` FROM apprenant AS a, gestionnaire AS g, versement AS v
    WHERE a.id_apprenant=v.id_apprenant AND g.id_gestionnaire=v.id_gestionnaire");
    $req->execute();
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./librairies/bootstrap.css">
    <link rel="stylesheet" href="./librairies/bootstrap.min.css">
    <link rel="stylesheet" href="./librairies/datatables.min.css">
    <link rel="stylesheet" href="./CSS/accueil.css">
    <link rel="stylesheet" href="./librairies/style.css">
    <link rel="shortcut icon" type="image/png" href="./images/Barcles.png" />
    <title>Versement</title>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark p-3 mb-5" id="navbar">
        <!-- <a class="navbar-brand" href="#">SPICES</a> -->
        <span><a href="versement.php"><img src="./images/Barcles.png" style="width: 50px; height: 50px; border-radius: 50%;" alt="NONE"></a></span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./deconnexion.php" style="color: #dc3545;">Deconnexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./admin.php">Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./accueil.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="./versement.php" style="border-bottom: 2px solid #007bff;">Versement</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" value="<?= number_format($ligne[0], 0, ',', ' ') ?>" type="text" placeholder="0" style="pointer-events: none; width: 100px; color: #748c93;">
            </form>
            <button class="btn btn-danger" data-toggle="modal" id="add" data-target="#search_param"><span class="fa fa-search"></button>

        </div>
    </nav>

    <!-- <div class="modal search_param" id="search_param">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-list fa-2x"></i>Recherche</h3>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" action="" method="POST">
                            <div class="row mt-4">
                                <div class="col-md-2">
                                    <label>Critère</label>
                                </div>
                                <div class="col-md-10">
                                    <select name="critere" class="form-control" id="critere" onchange="affiche_res()">
                                        <option value=""></option>
                                        <option value="Gestionnaire">Gestionnaire</option>
                                        <option value="Periode">Periode</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4" id="bloc_selection" style="visibility: hidden;">
                                <div class="col-md-2">
                                    <label>Sélection</label>
                                </div>
                                <div class="col-md-10">
                                    <select name="selection" class="form-control" id="selection">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 mt-4">
                                <div class="col-md-2">
                                    <label>Intervalle</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="datetime-local" name="date1" class="form-control">
                                </div>
                                <div class="col-md-5">
                                    <input type="datetime-local" name="date2" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button style="color: white; background: #032751;" name="envoi" id="search_params" type="submit" class="btn btn-block">Rechercher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
    <div class="modal search_param" id="search_param">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-list fa-2x"></i>Recherche</h3>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" action="" method="POST">
                            <div class="row mt-4 d-flex">
                                <div class="col-md-2">
                                    <label>Critère</label>
                                </div>
                                <div class="col-md-10 containt1">
                                    <select name="critere" style="display:block;" class="form-control" id="critere" onchange="affiche_res()">
                                        <option value=""></option>
                                        <option value="Gestionnaire">Gestionnaire</option>
                                        <option value="Periode">Periode</option>
                                        <option value="Scolarité">Scolarité</option>
                                    </select>
                                </div>
                                <div class="col-md-5 containt2" style=" display:none;">
                                    <select name="tranche" class="form-control" id="param1">
                                        <option value=""></option>
                                        <option value="tranche1">tranche1</option>
                                        <option value="tranche2">tranche2</option>
                                        <option value="tranche3">tranche3</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4 d-flex" id="bloc_selection">
                                <div class="col-md-2">
                                    <label>Sélection</label>
                                </div>
                                <div class="col-md-10 contenu1" style="display:block;">
                                    <select name="selection" class="form-control" id="selection">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-5 contenu2" style="display: none;">
                                    <select name="selection2" class="form-control" id="selection1">
                                        <option value=""></option>
                                    </select>
                                </div>
                                <div class="col-md-5 contenu3" style="display: none;">
                                    <select name="selection3" class="form-control" id="selection1">
                                        <option value=""></option>
                                        <option value="Payé">Payé</option>
                                        <option value="Non Payé">Non payé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4 mt-4">
                                <div class="col-md-2">
                                    <label>Intervalle</label>
                                </div>
                                <div class="col-md-5">
                                    <input type="datetime-local" name="date1" class="form-control">
                                </div>
                                <div class="col-md-5">
                                    <input type="datetime-local" name="date2" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button style="color: white; background: #032751;" name="envoi" id="search_params" type="submit" class="btn btn-block">Rechercher</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /////////////////////////////////////////////////////////////////////////////////////////////////// -->


    <div class="modal edi_pay" id="edi_pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <span><img id="img_appr" src="" style="width:70px; height:70px; border-radius:50%;" /></span>
                    <!-- <h3 class="modal-title text-white"><i class="fas fa-user-plus fa-2x"></i>Nouvelle formation</h3> -->
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" enctype="multipart/form-data">
                            <input type="text" style="display: none;" class="form-control" id="id_vers">

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Type</label>
                                    <select name="" class="form-control" id="type_paye">
                                        <option value="Espèce">Espèce</option>
                                        <option value="Paymaster">Paymaster</option>

                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Montant</label>
                                    <input type="text" class="form-control" id="mt_verse">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button style="color: white; background: #032751;" name="envoi" id="insert_form" type="button" class="btn btn-block" onclick="modif_vers()">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- table students -->

    <div class="container">

        <section class="table_user">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-wrap">
                            <table class="table" id="tbl_user3">
                                <thead class="thead-danger" style="background-color:#c76a6a;">
                                    <tr class="text-center bg-danger" style="color: black;">
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Apprenant</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Gestionnaire</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Montant</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Date_payement</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $req->fetch()) { ?>
                                        <tr class="alert text-center" role="alert">
                                            <td class="">
                                                <div class="pl-3 email">
                                                    <span class="text-center"><?= $row['nomApp']; ?></span>
                                                    <span class="text-center"><?= $row['prenom']; ?></span>
                                                </div>
                                            </td>
                                            <td class="">

                                                <div class="pl-3 email" style="background-color: rgb(240, 237, 237);">
                                                    <span class="text-center"><?= $row['nom']; ?></span>
                                                    <span class="text-center"><?= $row['prenomgest']; ?></span>
                                                </div>
                                            </td>
                                            <td id="xc<?= $row['id_versement'] ?>">
                                                <div class="text-center" data-target="montant"><?= number_format($row['montant'], 0, ',', ' '); ?></div>
                                            </td>
                                            <td>
                                                <div class="text-center"><?= $row['date']; ?></div>
                                            </td>
                                            <td style="display: none;" id="xcc<?= $row['id_versement'] ?>">
                                                <div class="text-center" data-target="type"><?= $row['type']; ?></div>
                                            </td>
                                            <td id="<?= $row['id_versement'] ?>" style="display: none;">
                                                <img data-target="img_app" src="./images/<?= $row['photos']; ?>" style="width: 70px; height: 70px; border-radius:50%;" alt="">
                                            </td>

                                            <?php
                                            $date = explode(" ", $row['date']);
                                            $Object = new DateTime();
                                            $moment = $Object->format("Y-m-d");
                                            if ($date[0] == $moment) { ?>
                                                <td>
                                                    <button onclick="active_edi_pay(<?= $row['id_versement'] ?>)" class="btn btn-danger mr-1" id="<?= $row['id_versement']; ?>" data-toggle="modal" data-target=".edi_pay"><i class="fa fa-edit"></i></button>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>


    <script src="./librairies/jquery.js"></script>
    <script src="./librairies/datatables.min.js"></script>
    <script src="./librairies/bootstrap.min.js"></script>
    <script src="./librairies/all.js"></script>
    <script src="./librairies/bootstrap.bundle.min.js"></script>
    <script src="./librairies/bootstrap.bundle.js"></script>
    <script src="./librairies/sweetalert2.all.min.js"></script>
    <script src="../traitement/administrateur/administrateur.js"></script>

</body>

</html>