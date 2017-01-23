<?php

require_once('autoload.inc.php');

$wp = new WebPage("Page d'accueil");

$wp->appendContent(<<<HTML
    <h1>Test de la classe WebPage</h1>
HTML
);

echo $wp->toHTML();
