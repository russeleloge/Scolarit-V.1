<?php
session_start();
$id_gerant = $_SESSION['id_user'];
if ($_SESSION['autoriser'] != "oui") {
    header(('Location: ../index.html'));
    exit();
}
require("./connect.php");
$req = $pdo->prepare("SELECT * FROM formation");
$req->execute();
$req1 = $pdo->prepare("SELECT id_apprenant,saison,ap.nomApp,f.nom,prenom,sexe,date_naissance,CNI,telephone,photos FROM apprenant As ap, formation As f 
                        WHERE ap.id_formation = f.id_formation");
$req1->execute();

// ####################################################################################
$Object = new DateTime();
$moment = $Object->format("Y-m-d");
$req_time = $pdo->prepare("SELECT SUM(montant) FROM versement AS v, gestionnaire AS g WHERE v.id_gestionnaire=g.id_gestionnaire AND g.id_gestionnaire='$id_gerant' AND v.id_gestionnaire='$id_gerant' AND `date` LIKE'%$moment%'");
$req_time->execute();
$res_date = $req_time->fetch();

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./librairies/bootstrap.css">
    <link rel="stylesheet" href="./librairies/bootstrap.min.css">
    <link rel="stylesheet" href="./CSS/accueil.css">
    <link rel="stylesheet" href="./librairies/style.css">
    <!-- <link rel="stylesheet" href="./librairies/style1.css"> -->
    <link rel="stylesheet" href="./librairies/datatables.min.css">
    <link rel="shortcut icon" type="image/png" href="./images/Barcles.png" />
    <title>Accueil</title>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark p-3 mb-5" id="navbar">
        <!-- <a class="navbar-brand" href="#">SPICES</a> -->
        <span><a href="accueil.php"><img src="./images/Barcles.png" style="width: 50px; height: 50px; border-radius: 50%;" alt="NONE"></a></span>
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
                    <a class="nav-link" href="./accueil.php" style="border-bottom: 2px solid #007bff;">Accueil</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" value="<?=number_format($res_date[0], 0, ',', ' ')?>" type="text" placeholder="0" style="pointer-events: none; width: 100px; color: #748c93;">
            </form>
            <button class="btn btn-danger mr-2" data-toggle="modal" data-target="#change_key"><span class="fas fa-key"></button>
            <button class="btn btn-primary" data-toggle="modal" id="add" data-target="#addStudent"><span class="fa fa-plus"></button>

        </div>
    </nav>
    <!-- position: relative; left: 50px; top: 20px; -->
    <!-- Modal students -->
    <div class="modal modif_app" id="addStudent">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--qui creer la fenetre blanche-->
                <div class="modal-header d-flex" style="background: #032751;">
                    <h3 class="modal-title text-white mr-5"><i class="fas fa-user-plus fa-2x"></i>Nouveau Apprenant</h3>
                    <span style=" display: none; width: 120px; margin-top: 20px;" id="content_mtn_ins"><input type="number" class="form-control mr-2" id="mtn_ins"></span>
                    <select name="" class="form-control ml-2" id="mod_pay" style="display: none; width: 120px; margin-top: 20px;">
                        <option value="Espèce">Espèce</option>
                        <option value="Paymaster">Paymaster</option>
                    </select>
                    <button type="button" id="reset" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" enctype="multipart/form-data">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Nom(s)</label>
                                    <input type="text" class="form-control" id="nom">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Prénom(s)</label>
                                    <input type="text" class="form-control" id="prenom">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12" id="bloc_naissance">
                                    <label>Date_de_naissance</label>
                                    <input type="date" class="form-control" id="date">
                                </div>
                                <div class="form-group col-md-6" id="bloc_intervalle" style="display:none;">
                                    <label>Intervalle</label>
                                    <select name="" class="form-control" id="intervalle">
                                        <option class="option1" value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Sexe</label>
                                    <select name="" class="form-control" id="sexe">
                                        <option value=""></option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Telephone</label>
                                    <input type="number" class="form-control" id="tel">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Formation</label>
                                    <select name="" class="form-control" id="formation">
                                        <option value=""></option>
                                        <?php while ($row = $req->fetch()) { ?>
                                            <option value="<?= $row['nom']; ?>"><?= $row['nom']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>saison</label>
                                    <select name="" class="form-control" onchange="affiche_intervalle()" id="saison">
                                        <option value=""></option>
                                        <option value="jour">Journée</option>
                                        <option value="soir">Soirée</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>CNI</label>
                                    <input type="text" class="form-control" id="cni">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Photo</label>
                                    <input type="file" name="imgs" class="form-control" id="myImage">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-5">
                                    <button style="color: white" name="envoi" type="reset" class="btn btn-danger btn-block">Effacer</button>
                                </div>
                                <div class="form-group col-md-1">
                                    <button class="btn btn-danger" type="button" id="paie"><span class="fa fa-money-check-alt"></button>
                                </div>
                                <div class="form-group col-md-6">
                                    <button style="color: white; background: #032751;" name="envoi" id="insert" type="button" onclick="insert_student()" class="btn btn-block">Enregistrer</button>
                                </div>


                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- update student -->
    <div class="modal eff_pay" id="eff_pay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <span><img id="img_appr" src="" style="width:70px; height:70px; border-radius:50%;" /></span>
                    <!-- <h3 class="modal-title text-white"><i class="fas fa-user-plus fa-2x"></i>Nouvelle formation</h3> -->
                    <button type="button" id="reset" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" enctype="multipart/form-data">
                            <input type="text" style="display: none;" class="form-control" id="id_appr">

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
                                    <input type="number" class="form-control" id="mtn_verse">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button style="color: white; background: #032751;" name="envoi" id="insert_form" type="button" onclick="eff_vers()" class="btn btn-block">Enregistrer</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Set password -->
    <div class="modal change_key" id="change_key">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-key fa-2x"></i>Password</h3>
                    <button type="button" id="reset" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" class="" enctype="multipart/form-data">

                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label>Ancien</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" class="form-control" id="ancien_mdp">
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-2">
                                    <label>Nouveau</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" id="nouveau_mdp" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-4 mt-2">
                                <div class="col-md-2">
                                    <label>Confirmer</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="password" id="confirm_mdp" class="form-control">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-8">
                                    <button style="color: white; background: #032751;" name="envoi" id="key" type="button" class="btn btn-block">Enregistrer</button>
                                    
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
                <!-- <div class="row justify-content-center">
				<div class="col-md-6 text-center mb-4">
					<h2 class="heading-section">Table #06</h2>
				</div>
			</div> -->
                <div class="row">
                    <div class="col-md-12">
                        <!-- <h3 class="h5 mb-4 text-center">Table Accordion</h3> -->
                        <div class="table-wrap">
                            <table class="table table-striped" id="tbl_student">
                                <thead class="thead-primary table">
                                    <tr class="text-center" style="color: black;">
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Appelation</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Sexe</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Naissance</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">CNI</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Telephone</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Formation</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Saison</th>
                                        <th class="text-center" style="font-weight:bold; font-size:15px; color: black;">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $req1->fetch()) { ?>
                                        <tr class="alert text-center" role="alert">
                                            <td class="d-flex align-items-center">
                                                <div class="" onclick="active_recu(<?= $row['id_apprenant']; ?>)" id="<?= $row['id_apprenant']; ?>"><img data-target="img_app" src="./images/<?= $row['photos']; ?>" style="width: 70px; height: 70px; border-radius:50%;" alt=""></div>
                                                <div class="pl-3 email" id="nn<?php echo $row['id_apprenant']; ?>">
                                                    <span data-target="nomApp"><?= $row['nomApp']; ?></span>
                                                    <span data-target="prenomApp"><?= $row['prenom']; ?></span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text" id="<?php echo $row['id_apprenant']; ?>" data-target="sexe"><?= $row['sexe']; ?></div>
                                            </td>
                                            <td>
                                                <div class="text" data-target="date_naissance"><?= $row['date_naissance']; ?></div>
                                            </td>
                                            <td>
                                                <div class="text" data-target="cni"><?= $row['CNI']; ?></div>
                                            </td>
                                            <td>
                                                <div class="text" data-target="telephone"><?= $row['telephone']; ?></div>
                                            </td>
                                            <td>
                                                <div class="text" data-target="nomFor"><?= $row['nom']; ?></div>
                                            </td>
                                            <td>
                                                <div class="text" data-target="saison"><?= $row['saison']; ?></div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <button onclick="active_modif_app(<?= $row['id_apprenant']; ?>)" data-toggle="modal" data-target=".modif_app" value="<?php echo $row['id_apprenant']; ?>" class="btn btn-danger mr-1">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button onclick="active_pay_app(<?= $row['id_apprenant']; ?>)" data-toggle="modal" data-target=".eff_pay" class="mr-1 btn btn-danger">
                                                        <i class="fa fa-money-check-alt"></i>
                                                    </button>
                                                    <button class="btn btn-primary myPopovers" id="<?= $row['id_apprenant']; ?>">
                                                        <i class="fa fa-tasks"></i>
                                                    </button>
                                                </div>

                                            </td>
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
    <script src="../traitement/gestionnaire/gestionnaire.js"></script>

</body>

</html>