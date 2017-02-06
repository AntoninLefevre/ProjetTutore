<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(!$user->isAdministrator){
    header("Location: index.php");
}



$wp = new WebPage('Administration', false);

$wp->appendCssUrl('../style/default/style.css');
$wp->appendContent($user->formOptionsSite());

echo $wp->toHTML();
