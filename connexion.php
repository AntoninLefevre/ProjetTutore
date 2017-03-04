<?php

require_once("autoload.inc.php");

$user = User::createFromSession();

if(isset($_SESSION['connected']) && $_SESSION['connected']){
    header("Location: index.php");
}

if(isset($_POST['formConnection'])){
    $res = User::createFromAuth($_POST);

    if($res === false){
        $formConnection = User::formConnection("L'identifiant et le mot de passe ne correspondent pas", $_POST);
    } elseif($res instanceof User){
        $res->saveIntoSession();
        header("Location: index.php");
    } else {
        $formConnection = User::formConnection($res, $_POST);

    }
} else {
    if(isset($_GET['i']) && isset($_GET['action'])){
        if($_GET['action'] == "valid"){
            if($_GET['i'] == "error"){
                $formConnection = User::formConnection("Erreur lors de la validation de l'email");
            } else {
                $formConnection = User::formConnection("Compte validé. Vous pouvez vous connecter");
            }
        } elseif($_GET['action'] == "reset") {
            if($_GET['i'] == "confirm"){
                $formConnection = User::formConnection("Un e-mail vous a été envoyé");
            } elseif($_GET['i'] == "error"){
                $formConnection = User::formConnection("Erreur lors de la demande de modification du mot de passe");
            } else {
                $formConnection = User::formConnection("Votre mot de passe a été modifié");
            }
        } elseif($_GET['action'] == "delete"){
            if($_GET['i'] == "admin"){
                $formConnection = User::formConnection("Vous ne pouvez pas supprimer votre compte car vous êtes administrateur");
            } else {
                $formConnection = User::formConnection("Votre compte a été supprimé");
            }
        } else {
            $formConnection = User::formConnection();
        }
    } else {
        $formConnection = User::formConnection();
    }
}

$wp = new WebPage($siteOptions['siteName'] . " - " . "Connexion");

$wp->appendContent($formConnection);

echo $wp->toHTML();
