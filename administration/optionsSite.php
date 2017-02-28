<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(!$user->isAdministrator){
    header("Location: index.php");
}

$wp = new WebPage('Administration', false);

if(isset($_POST['formOptionsSite'])){
    Administrator::editOptionSite($_POST);
    header("Location: optionsSite.php");
}

$wp->appendContent(Administrator::formOptionsSite());

echo $wp->toHTML();
