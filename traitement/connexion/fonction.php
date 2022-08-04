<?php
session_start();
require_once('../../public/connect.php');
// $id_gestionnaire;

function connexion()
{
    global $pdo;
    global $id_gestionnaire;
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $autoriser = "oui";
    $req = $pdo->prepare("SELECT * FROM gestionnaire WHERE username='$username' AND password='$password' AND autorisation='$autoriser'");
    $req->execute();
    $row = count($req->fetchAll());

// password_verify()
    if ($row != 0) {
        // 
        $req = $pdo->prepare("SELECT * FROM gestionnaire WHERE username='$username' AND password='$password'");
        $req->execute();
        $elt = $req->fetch();
        // 
        $_SESSION['autoriser'] = "oui";
        $_SESSION['id_user'] = $elt['id_gestionnaire'];
        // $id_gestionnaire = $elt['id_gestionnaire'];

        $req = $pdo->prepare("SELECT * FROM gestionnaire WHERE username='$username' AND password='$password' AND privilege='administrateur'");
        $req->execute();
        $exe = count($req->fetchAll());

        if ($exe != 0) {
            $_SESSION['admin'] = "yes";
        }
    } else {
        echo "false";
    }
}

