<?php

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
                    self::$_optionSite[$value['nameOptionSite']] = $value['valueOptionSite'];
                }
            }
            else {
                return false;
            }
        }
        return self::$_optionSite;
    }
}
