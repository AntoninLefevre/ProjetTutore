<?php
error_reporting(E_ALL|E_STRICT);
date_default_timezone_set('Europe/Paris');

spl_autoload_register(function($class){
	$fichier = "../classes/" . $class .'.class.php';
    if (file_exists($fichier))
	   require_once($fichier);

    require_once('../mypdo.include.php');
});
