<?php

require_once("autoload.inc.php");

$wp = new WebPage($siteOptions['siteName'] . " - " . "Page non trouvée");
$wp->appendContent("<div class='row text-center'><h1>Page non trouvée</h1></div>");

echo $wp->toHTML();
