<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(!$user->isAdministrator){
    header("Location: index.php");
}

$wp = new WebPage('Membres', false);

if(isset($_GET['id'])){
    if(isset($_GET['a'])){
        $user = User::getUser($_GET['id']);
        if(!$user){
            $wp->appendContent(Administrator::listUsers());
        } else {
            if($_GET['a'] == 'e'){
                if(isset($_POST['formEditProfileUser'])){
                    if(isset($_POST['nickname']) && isset($_POST['email'])){
                        Administrator::editProfileUser($_POST, $_GET['id']);
                        $user = User::getUser($_GET['id']);
                        $formEditProfileUser = Administrator::formEditProfileUser($user, "Le membre a bien été modifié");
                    } else {
                        $formEditProfileUser = Administrator::formEditProfileUser($user, "Erreur lors de la modification du membre");
                    }
                } else {
                    $formEditProfileUser = Administrator::formEditProfileUser($user);
                }
                $wp->appendContent($formEditProfileUser);
            } elseif($_GET['a'] == 'd'){
                if(isset($_POST['formDeleteProfileUser'])){
                    if($user->isAdministrator){
                        $formDeleteProfileUser = Administrator::formDeleteProfileUser($user, "Vous ne pouvez pas supprimer un administrateur");
                    } else {
                        Administrator::deleteProfileUser($_GET['id']);
                        $formDeleteProfileUser = Administrator::listUsers();
                    }
                } elseif(isset($_POST['cancelDeleteProfileUser'])){
                    header("Location: users.php");
                } else {
                    $formDeleteProfileUser = Administrator::formDeleteProfileUser($user);
                }
                $wp->appendContent($formDeleteProfileUser);
            } else {
                $wp->appendContent(Administrator::listUsers());
            }
        }
    }
} else {
    $wp->appendContent(Administrator::listUsers());
}


echo $wp->toHTML();
