<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Paris');

spl_autoload_register(function($class){
    $fichier = "../classes/" . $class .'.class.php';
    if (file_exists($fichier))
       require_once($fichier);
});

$configsql = parse_ini_file("../configsql.ini");
MyPDO::setConfiguration('mysql:host='.$configsql['host'].';dbname='.$configsql['dbname'].';charset=utf8', $configsql['username'], $configsql['password']);

$siteOptions = Site::getOptions();
