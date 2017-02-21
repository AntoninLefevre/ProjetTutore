<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(!$user->isAdministrator){
    header("Location: index.php");
}

$wp = new WebPage('Administration', false);

$wp->appendCssUrl('../style/' . Site::getOptions()['theme'] . '/style.css');

if(isset($_POST['formOptionsSite'])){
    Administrator::editOptionSite($_POST);
}

$wp->appendContent(Administrator::formOptionsSite());

echo $wp->toHTML();
