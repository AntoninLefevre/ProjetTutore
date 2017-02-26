<?php

require_once('autoload.inc.php');

$wp = new WebPage("Accueil");

$wp->appendContent("<h1>Page d'accueil</h1>");

echo $wp->toHTML();
