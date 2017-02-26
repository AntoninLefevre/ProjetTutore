<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

$wp = new WebPage('Administration', false);
$wp->appendContent("<h1>Administration</h1>");

echo $wp->toHTML();
