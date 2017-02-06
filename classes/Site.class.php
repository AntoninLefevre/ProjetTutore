<?php

require_once("MyPDO.class.php");

final class Site {

    private static $_optionSite = null;

    private  function __construct() {

    }

    public  function __clone() {
        throw new Exception("Clonage interdit");
    }

    public static function getOptions() {
        if (is_null(self::$_optionSite)) {
            if ($bdd = MyPDO::getInstance()) {
                $pdo = $bdd->prepare("SELECT * FROM optionSite");
                $pdo->execute();
                foreach ($pdo->fetchAll() as $value) {
                    //var_dump($value);
                    self::$_optionSite[$value['nameOptionSite']] = $value['valueOptionSite'];
                }
                //self::$_optionSite = $pdo->fetchAll();
            }
            else {
                return false;
            }
        }
        return self::$_optionSite;
    }
}
