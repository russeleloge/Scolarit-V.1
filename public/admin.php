<?php
session_start();
if ($_SESSION['admin'] != "yes") {
    header("Location: ./accueil.php");
    exit();
}

require("./connect.php");

$req = $pdo->prepare("SELECT * FROM gestionnaire");
$req->execute();

$req1 = $pdo->prepare("SELECT * FROM formation");
$req1->execute();
$disabled = "";
$autorisation = "";
$req2 = $pdo->prepare("SELECT * FROM tarif");
$req2->execute();

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
    <link rel="stylesheet" href="./librairies/datatables.min.css">
    <link rel="shortcut icon" type="image/png" href="./images/Barcles.png" />
    <title>Admin</title>
</head>

<body ng-app>

    <nav class="navbar navbar-expand-lg navbar-dark p-3 mb-4" id="navbar">
        <span><a href="admin.php"><img src="./images/Barcles.png" style="width: 50px; height: 50px; border-radius: 50%;" alt="NONE"></a></span>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".navbarSupportedContent" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse navbarSupportedContent" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./deconnexion.php" style="color: #dc3545;">Deconnexion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./versement.php">Versement</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./accueil.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./admin.php" style="border-bottom: 2px solid #007bff;">Admin</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Options
                    </a>
                    <div class="dropdown-menu bg-danger" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item w1" onclick="closeOther0()">Gestionnaires</a>
                        <a class="dropdown-item w2" onclick="closeOther1()">Formations</a>
                        <a class="dropdown-item w3" onclick="closeOther2()">Tarifs</a>
                    </div>
                </li>
            </ul>
            <button class="btn btn-danger" data-toggle="modal" id="adUser" data-target=".addUser"><span class="fa fa-user"></button>
            <button class="btn btn-danger" style="display: none;" data-toggle="modal" id="adFormation" data-target=".addFormation"><span class="fa fa-file"></button>
            <button class="btn btn-danger" style="display: none;" data-toggle="modal" id="adTarif" data-target=".addTarif"><span class="fa fa-form"></button>
        </div>
    </nav>

    <!-- form add user -->
    <div class="modal addUser" id="addUser">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!--qui creer la fenetre blanche-->
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-user-plus fa-2x"></i>Nouveau Sécrétaire</h3>
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
                                <div class="form-group col-md-6">
                                    <label>Sexe</label>
                                    <select name="" class="form-control" id="sexe">
                                        <option value=""></option>
                                        <option value="M">M</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Date_de_naissance</label>
                                    <input type="date" class="form-control" id="date">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Telephone1</label>
                                    <input type="number" class="form-control" id="tel1">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Telephone2</label>
                                    <input type="number" class="form-control" id="tel2">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>CNI</label>
                                    <input type="text" class="form-control" id="cni">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Username</label>
                                    <input type="text" class="form-control" id="user">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <button style="color: white" name="envoi" type="reset" class="btn btn-danger btn-block">Effacer</button>
                                </div>
                                <div class="form-group col-md-6">
                                    <button style="color: white; background: #032751;" name="envoi" id="insert" type="button" onclick="insert_user()" class="btn btn-block">Enregistrer</button>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- form add & update formation -->
    <div class="modal addFormation" id="addFormation">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-user-plus fa-2x"></i>Nouvelle formation</h3>
                    <button type="button" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div class="modal-body" style="color: black;">
                        <form id="modal-form" enctype="multipart/form-data">
                            <input type="text" style="display: none;" class="form-control" id="id_formation">

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Nom</label>
                                    <input type="text" class="form-control" id="nom_formation">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Série</label>
                                    <select name="" class="form-control" id="serie_formation">
                                        <option id="option1" value=""></option>
                                        <option value="informatique">Informatique</option>
                                        <option value="bureautique">Bureautique</option>
                                        <option value="comptabilite">Comptabilité</option>
                                        <option value="expression">Expression</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <button style="color: white; background: #032751;" name="envoi" id="insert_form" type="button" onclick="insert_formation()" class="btn btn-block">Enregistrer</button>
                                    <button style="color: white; background: #032751; display: none;" name="envoi" id="update_form" type="button" onclick="edit_formation()" class="btn btn-block">Modifier</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- form add & update tarif -->
    <div class="modal addTarif" id="addTarif">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: #032751;">
                    <h3 class="modal-title text-white"><i class="fas fa-user-plus fa-2x"></i>Nouveau tarif</h3>
                    <button type="button" id="reset" style="outline: none;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div>
                    <div>
                        <div class="modal-body" style="color: black;">
                            <form id="modal-form">

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Série</label>
                                        <select name="" class="form-control" id="serie_form">
                                            <option id="option1" value=""></option>
                                            <option value="informatique">Informatique</option>
                                            <option value="bureautique">Bureautique</option>
                                            <option value="comptabilite">Comptabilité</option>
                                            <option value="expression">Expression</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Saison</label>
                                        <select name="" class="form-control" id="saison">
                                            <option value=""></option>
                                            <option value="jour">Jour</option>
                                            <option value="soir">Soir</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Début</label>
                                        <input type="date" class="form-control" id="debut">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fin</label>
                                        <input type="date" class="form-control" id="fin">
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Tranche1</label>
                                        <input type="number" class="form-control" id="tranche1">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date_limite_1</label>
                                        <input type="date" class="form-control" id="date_lim_1">
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Tranche2</label>
                                        <input type="number" class="form-control" id="tranche2">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date_limite_2</label>
                                        <input type="date" class="form-control" id="date_lim_2">
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Tranche3</label>
                                        <input type="number" class="form-control" id="tranche3">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Date_limite_3</label>
                                        <input type="date" class="form-control" id="date_lim_3">
                                    </div>
                                </div>
                                <!--  -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <button style="color: white" name="envoi" type="reset" class="btn btn-danger btn-block">Effacer</button>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button style="color: white; background: #032751;" name="envoi" id="insert" type="button" onclick="insert_tarif()" class="btn btn-block">Enregistrer</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <section class="table_formation" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-wrap">
                        <table class="table" id="tbl_user2">
                            <thead class="thead-primary">
                                <tr class="text-center">
                                    <th class="text-center">Formations</th>
                                    <th class="text-center">Série</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $req1->fetch()) { ?>
                                    <tr class="alert text-center" id="<?php echo $row['id_formation']; ?>" role="alert">

                                        <td data-target="nom_formation">
                                            <?= $row['nom'] ?>
                                        </td>
                                        <td data-target="serie_formation">
                                            <?= $row['serie'] ?>
                                        </td>

                                        <td>
                                            <?php if ($row['control'] == 0) { ?>
                                                <button onclick="sup_formation(y<?= $row['id_formation']; ?>)" id="y<?= $row['id_formation']; ?>" class="btn btn-danger <?= $row['nom'] ?>"><i class="fa fa-trash"></i></button>
                                            <?php } ?>
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
   
    
    <!--  -->
    <section class="table_tarif" style="display: none;">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!-- <h3 class="h5 mb-4 text-center">Table Accordion</h3> -->
                    <div class="table-wrap">
                        <table class="table" id="tbl_user1">
                            <thead class="thead-primary">
                                <tr class="text-center" style="color:black;">
                                    <th class="text-center">Serie</th>
                                    <th class="text-center">Saison</th>
                                    <th class="text-center">Début</th>
                                    <th class="text-center">Date_fin</th>
                                    <th class="text-center">Tranche1</th>
                                    <th class="text-center">Tranche2</th>
                                    <th class="text-center">Tranche3</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $req2->fetch()) { ?>
                                    <tr class="alert text-center" role="alert">
                                        <td>
                                            <div><?= $row['serie'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $row['saison'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $row['debut'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $row['fin'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= number_format($row['tranche1'], 0, ',', ' ') ?></div>
                                        </td>
                                        <td>
                                            <div><?= number_format($row['tranche2'], 0, ',', ' ') ?></div>
                                        </td>
                                        <td>
                                            <div><?= number_format($row['tranche3'], 0, ',', ' ') ?></div>
                                        </td>
                                        <td>
                                            <div><?= number_format($row['total'], 0, ',', ' ') ?></div>
                                        </td>
                                        <td>
                                            <div>
                                                <?php ?>
                                                <button class="btn btn-danger"><i class="fa fa-edit"></i></button>
                                                <?php ?>

                                                <button class="btn btn-danger"><i class="fa fa-list"></i></button>
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
    
    <!--  -->
    <section class="table_user">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-wrap">
                        <table class="table" id="tbl_user">
                            <thead class="thead-primary" style="background-color:#007bff;">
                                <tr class="text-center">
                                    <th class="text-center">Nom(s) & Prenom(s)</th>
                                    <th class="text-center">Sexe</th>
                                    <th class="text-center">Age</th>
                                    <th class="text-center">Telephone</th>
                                    <th class="text-center">CNI</th>
                                    <th class="text-center">Privilège</th>
                                    <th class="text-center">Autorisation</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $req->fetch()) { ?>
                                    <tr class="alert text-center" role="alert">
                                        <td>
                                            <div class="email">
                                                <span><?= $row['nom'] ?> </span>
                                                <span><?= $row['prenomgest'] ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($row['sexe'] == 'F') { ?>
                                                <div class=""><img src="../public/images/woman.png" style="width: 20px;" alt=""></div>
                                            <?php  } else { ?>
                                                <div class=""><img src="../public/images/man.png" style="width: 20px;" alt=""></div>
                                            <?php } ?>

                                        </td>
                                        <td>
                                            <div><?= $row['date_naissance'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $row['telephone1'] ?></div>
                                        </td>
                                        <td>
                                            <div><?= $row['CNI'] ?></div>
                                        </td>
                                        <td>
                                            <div>
                                                <?= $row['privilege'];  ?>
                                            </div>
                                        </td>
                                        <?php
                                        if ($row['privilege'] == "administrateur") {
                                            $disabled = "disabled";
                                        } else {
                                            $disabled = "";
                                        }

                                        if ($row['autorisation'] == "oui") {
                                            $autorisation = "checked";
                                        } else {
                                            $autorisation = "";
                                        }

                                        ?>
                                        <td>
                                            <div class="d-flex">
                                                <span class="custom-control custom-switch control-event-click p-2">
                                                    <input type="checkbox" class="custom-control-input" onclick="control_check(c<?= $row['id_gestionnaire'] ?>)" id="c<?= $row['id_gestionnaire'] ?>" <?= $disabled; ?> <?= $autorisation; ?>>
                                                    <label class="custom-control-label" for="c<?= $row['id_gestionnaire'] ?>"></label>
                                                </span>
                                                <button class="btn btn-danger <?= $row['nom'] ?> <?= $row['prenomgest'] ?>" id="x<?= $row['id_gestionnaire'] ?>" onclick="reset_password(x<?= $row['id_gestionnaire'] ?>)"><i class="fas fa-key"></i></button>
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
    
  

    <script src="./librairies/jquery.js"></script>
    <script src="./librairies/datatables.min.js"></script>
    <script src="./librairies/all.js"></script>
    <script src="./librairies/bootstrap.bundle.min.js"></script>
    <script src="./librairies/sweetalert2.all.min.js"></script>
    <script src="./librairies/bootstrap.js"></script>
    <script src="./librairies/bootstrap.min.js"></script>
    <script src="../traitement/connexion/connexion.js"></script>
    <script src="../traitement/administrateur/administrateur.js"></script>

</body>

</html>