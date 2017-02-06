<?php

require_once('autoload.inc.php');

$wp = new WebPage("Accueil");

$wp->appendCssUrl('style/default/style.css');

$wp->appendContent("<h1>Page d'accueil</h1>");

$site = Site::getOptions();

echo $wp->toHTML();
