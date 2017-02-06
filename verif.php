<?php

require_once("autoload.inc.php");

if(isset($_GET['action']) && isset($_GET['email']) && isset($_GET['code'])){
    if($_GET['action'] == "valid"){
        if(User::verifCode($_GET) === false){
            header('Location: connexion.php?action=valid&i=error');
        } else {
            header('Location: connexion.php?action=valid&i=valid');
        }
    } elseif ($_GET['action'] == "resetPW"){
        if(User::verifCode($_GET) === false){
            header('Location: connexion.php?action=reset&i=error');
        } else {
            header('Location: resetPassword.php?action=reset&email=' . $_GET['email'] . '&code=' . $_GET['code']);
        }
    } elseif($_GET['action'] == "delete"){
        if(User::verifCode($_GET) === false){
            header('Location: connexion.php?action=delete&i=error');
        } else {
            $res = User::validDeleteUser($_GET['email']);
            if($res === false){
                if(User::isConnected()){
                    header('Location: profile.php?action=delete&i=admin');
                } else {
                    header('Location: connexion.php?action=delete&i=admin');
                }
            } else {
                User::logout();
                header('Location: connexion.php?action=delete&i=valid');
            }
        }
    } else {
        header('Location: connexion.php');
    }
} else {
    header('Location: connexion.php');
}
