<?php

require_once('autoload.inc.php');



if(isset($_POST['formEditEmail'])){
    $res = $user->editEmail($_POST['email']);
    if($res === true){
        $formEditEmail = $user->formEditEmail($user->emailUser, "Votre adresse e-mail a été modifiée");
    } elseif($res === -1){
        $formEditEmail = $user->formEditEmail($_POST['email'], "L'adresse e-mail est déjà utilisée");
    } else {
        $formEditEmail = $user->formEditEmail($_POST['email'], "L'adresse e-mail est invalide");
    }
    $formDeleteUser = $user->formDeleteUser();
} elseif(isset($_POST['formDeleteUser'])){
    $res = $user->deleteUser($_POST['password']);
    if($res === true){
        $user->deleteUserEmail();
        $formDeleteUser = $user->formDeleteUser("Un e-mail de validation a été envoyé à votre adresse e-mail");
    } else {
        $formDeleteUser = $user->formDeleteUser("Mot de passe incorrect");
    }
    $formEditEmail = $user->formEditEmail($user->emailUser);
} else {
    $formEditEmail = $user->formEditEmail($user->emailUser);
    $formDeleteUser = $user->formDeleteUser();
}


$wp = new WebPage("Profil");

$wp->appendCssUrl("style/default/style.css");

if($user->isAdministrator && isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['i']) && $_GET['i'] == "admin" ){
    $wp->appendContent("Vous ne pouvez pas supprimer votre compte car vous êtes administrateur");
}

$wp->appendContent($formEditEmail);
$wp->appendContent($formDeleteUser);

echo $wp->toHTML();
