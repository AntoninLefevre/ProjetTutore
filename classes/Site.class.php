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
                $pdo = $bdd->prepare("SELECT * FROM " . PREFIXTABLE ."optionsite");
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

    public static function formConfig($data = [], $info = ""){
        $host = isset($data['host']) ? $data['host'] : "";
        $dbname = isset($data['dbname']) ? $data['dbname'] : "";
        $username = isset($data['username']) ? $data['username'] : "";
        $tablePrefix = isset($data['tablePrefix']) ? $data['tablePrefix'] : "cms_";

        $siteName = isset($data['siteName']) ? $data['siteName'] : "";
        $siteDescription = isset($data['siteDescription']) ? $data['siteDescription'] : "";
        $adminEmail = isset($data['adminEmail']) ? $data['adminEmail'] : "";
        $nicknameUser = isset($data['nicknameUser']) ? $data['nicknameUser'] : "";

        $html = <<<HTML
        <form action="" method="post">
            $info
            <fieldset>
                <legend>Configuration SQL</legend>
                <label>Nom de l'hote*: <input type="text" name="host" value="$host" required></label>
                <label>Nom de la base de donnée*: <input type="text" name="dbname" value="$dbname" required></label>
                <label>Nom d'utilisateur*: <input type="text" name="username" value="$username" required></label>
                <label>Mot de passe: <input type="password" name="password"></label>
                <label>Prefix de table*: <input type="text" name="tablePrefix" value="$tablePrefix" required></label>
            </fieldset>
            <fieldset>
                <legend>Configuration générale</legend>
                <label>Nom du site*: <input type="text" name="siteName" value="$siteName" required></label>
                <label>Description du site: <input type="text" name="siteDescription" value="$siteDescription"></label>
                <label>Adresse e-mail*: <input type="email" name="adminEmail" value="$adminEmail" required></label>
                <label>Nom d'utilisateur*: <input type="text" name="nicknameUser" value="$nicknameUser" pattern=".{5,20}" required></label>
                <label>Mot de passe*: <input type="password" name="passwordUser" pattern=".{8,}" required></label>
            </fieldset>
            <input type="submit" name="formConfig">
        </form>
HTML;

        return $html;
    }
}
