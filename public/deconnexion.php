<?php
session_start();
session_destroy();
header("Location: ../index.html");

// ca c'est une page tuinelle car elle n'est pas visible sur le navigateur
// mais a un role en background, elle va ecraser la variable de session et
// et effectuer une redirection sans que l'utilisateur ne se rende compte
