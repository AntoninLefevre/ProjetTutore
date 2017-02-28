<?php


spl_autoload_register(function($class){
    $fichier = "classes/" . $class .'.class.php';
    if (file_exists($fichier))
       require_once($fichier);
});
if(file_exists("configsql.ini") ){

    $namePage = explode("/", $_SERVER['PHP_SELF']);
    if($namePage[sizeof($namePage)-1] == "install.php")
        header("Location: index.php");

    $configsql = parse_ini_file("configsql.ini");
    define("PREFIXTABLE", $configsql['tablePrefix']);

    error_reporting(E_ALL|E_STRICT);
    date_default_timezone_set('Europe/Paris');

    MyPDO::setConfiguration('mysql:host='.$configsql['host'].';dbname='.$configsql['dbname'].';charset=utf8', $configsql['username'], $configsql['password']);

    $siteOptions = Site::getOptions();

    User::startSession();


    if(!isset($_SESSION['user']) && isset($_COOKIE['user'])){
        $cookie = explode("----", $_COOKIE['user']);
        $res = User::userByCookie($cookie);
        if($res)
            $res->saveIntoSession();
    }

    $user = User::createFromSession();
} else{
    $namePage = explode("/", $_SERVER['PHP_SELF']);
    if($namePage[sizeof($namePage)-1] != "install.php")
        header("Location: install.php");
}
