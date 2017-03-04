<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(isset($_SESSION['connected']) && $_SESSION['connected']){
    header("Location: index.php");
}

if(isset($_POST['formAdd'])){
    $res = User::addUser($_POST);
    if($res === true){
        $formAddUser = User::formAddUser(array(<<<HTML
            <div>Vous êtes bien inscrit.<br>
            Un e-mail a été envoyé sur votre adresse e-mail<br>
            Merci de cliquer sur le lien de validation pour pouvoir vous connecter</div>
HTML
            ), $_POST);
    } else {
        $formAddUser = User::formAddUser($res, $_POST);
    }
} else {
    $formAddUser = User::formAddUser();
}

$wp = new WebPage($siteOptions['siteName'] . " - " . "Inscription");

$wp->appendContent($formAddUser);
echo $wp->toHTML();
