<?php

require_once("autoload.inc.php");

if(isset($_POST['formResetPasswordEmail'])){
    if(User::resetPasswordEmail($_POST['email'])){
        header('Location: connexion.php?action=reset&i=confirm');
    } else {
        $formResetPassword = User::formResetPasswordEmail($_POST['email']);
    }
} elseif(isset($_POST['formResetPassword'])){
    $res = User::updateResetPassword($_POST);
    if($res === false){
        $formResetPassword = User::formResetPassword("Le mot de passe doit faire au moins 8 caractères.");
    } else {
        header('Location: connexion.php?action=reset&i=valid');
    }
} else {
    if(isset($_GET['action']) && $_GET['action'] == "reset" && isset($_GET['email']) && isset($_GET['code'])){
        $formResetPassword = User::formResetPassword();
    } else {
        $formResetPassword = User::formResetPasswordEmail();
    }
}

$wp = new WebPage($siteOptions['siteName'] . " - " . "Oubli de mot de passe");

$wp->appendContent($formResetPassword);

echo $wp->toHTML();
