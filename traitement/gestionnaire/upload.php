<?php

// if (isset($_FILES['my_image'])) {
require('../../public/connect.php');
$img_name = $_FILES['my_image']['name'];
$img_size = $_FILES['my_image']['size'];
$tmp_name = $_FILES['my_image']['tmp_name'];
$error = $_FILES['my_image']['error'];

if ($error === 0) {
    if ($img_size > 1000000) {
        $em = "Sorry, your file is too large.";
        $error = array('error' => 1, 'em' => $em);
        echo json_encode($error);
        exit();
    } else {
        // pour recuperer l'extension de l'image
        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
        $img_ex_lc = strtolower($img_ex);

        $allowed_exs = array('jpg', 'jpeg', 'png');
        // Si l'extension de l'image se trouve dans le tableau d'elements
        if (in_array($img_ex_lc, $allowed_exs)) {
            // On renomme l'image
            $new_img_name = $img_name;
            // On upload l'image dans le dossier en question
            $img_upload_path = "../../public/images/" . $new_img_name;
            move_uploaded_file($tmp_name, $img_upload_path);
            // Ici on peut faire l'insertion
            $req = $pdo->prepare("SELECT * FROM apprenant ORDER BY id_apprenant DESC LIMIT 1");
            $req->execute();
            $recup = $req->fetch();
            $id = $recup['id_apprenant'];
            $req = $pdo->prepare("UPDATE apprenant SET photos='$new_img_name' WHERE id_apprenant='$id'");
            $req->execute();

            // $res = array('error' => 0, 'src' => $new_img_name);
            // echo json_encode($res);
            echo "true";
            // exit();
        } else {
            $em = "You can't upload files of this type.";
            $error = array('error' => 1, 'em' => $em);

            echo json_encode($error);
            exit();
        }
    }
} else {
    $em = "unknow error occured";
    $error = array('error' => 1, 'em' => $em);

    echo json_encode($error);
    exit();
}
// }
