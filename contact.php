<?php

require_once('autoload.inc.php');

$wp = new WebPage($siteOptions['siteName'] . " - " . "Contact");

if(isset($_POST['formContact'])){
    if(isset($_POST['subject']) && isset($_POST['email']) && isset($_POST['message'])){
        if(!empty($_POST['subject']) && !empty($_POST['email']) && !empty($_POST['message'])){
            ini_set('sendmail_from', $_POST['email']);
            if(User::contact($_POST)){
                $formContact = User::formContact(array(), "E-mail envoyé");
            } else {
                $formContact = User::formContact($_POST, "Adresse e-mail incorrecte");
            }
        } else {
            $formContact = User::formContact($_POST, "Tous les champs doivent être remplis");
        }
    } else {
        $formContact = User::formContact($_POST, "Erreur lors de l'envoi de l'e-mail");
    }
} else {
    $formContact = User::formContact();
}
$wp->appendContent($formContact);

echo $wp->toHTML();
