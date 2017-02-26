<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

$wp = new WebPage("Messages privés");

$wp->appendCssUrl("style/" . $siteOptions['theme'] . "/style.css");

if(isset($_GET['id'])){
    $pm = PrivateMessage::getPM($_GET['id']);
    if($pm){
        if(!$pm->isRead){
            $pm->setIsRead();
        }
        $wp->appendContent($pm->displayPM());

        if(isset($_POST['formReplyPM'])){
            if(isset($_POST['title']) && isset($_POST['content'])){
                $user = User::getUser($pm->idSender);
                $_POST['receiver'] = $user->nicknameUser;
                $res = PrivateMessage::sendPM($_POST);
                if($res){
                    $wp->appendContent($user->formReplyPM($_POST, "Le message a été envoyé"));
                } else {
                    $wp->appendContent($user->formReplyPM($_POST, "Le message a été envoyé"));
                }
            } else {
                $wp->appendContent($user->formReplyPM($_POST, "Le destinataire n'existe pas"));
            }
        } else {
            $wp->appendContent($user->formReplyPM(array("defaultTitle" => $pm->titlePM)));
        }
    } else {
        header("Location: privatemessages.php");
    }
} elseif(isset($_POST['formSendPM'])){
    if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['receiver'])){
        if($_POST['receiver'] == $user->nicknameUser){
            $wp->appendContent($user->formSendPM(array(), "Vous ne pas vous envoyer de message"));
        } else {
            $res = PrivateMessage::sendPM($_POST);
            if($res){
                $wp->appendContent($user->formSendPM(array(), "Le message a été envoyé"));
            } else {
                $wp->appendContent($user->formSendPM($_POST, "Le destinataire n'existe pas"));
            }
        }
    } else {
        $wp->appendContent($user->formSendPM($_POST, "Erreur lors de l'envoi du message"));
    }
} else {
    $PMs = PrivateMessage::getPMsReceiv($user->idUser);
    if($PMs){
        $wp->appendContent(PrivateMessage::displayPMs($PMs));
    } else {
        $wp->appendContent("Vous n'avez reçu aucun message");
    }
    $wp->appendContent($user->formSendPM());
}

echo $wp->toHTML();
