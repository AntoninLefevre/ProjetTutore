<?php

require_once('autoload.inc.php');


$url = explode("/", $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
unset($url[sizeof($url) - 1]);
$url = "http://" . implode("/", $url) . "/";


if(isset($_POST['formConfig'])){
    if(isset($_POST['host']) && isset($_POST['dbname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['tablePrefix'])){
        MyPDO::setConfiguration('mysql:host='.$_POST['host'].';dbname='.$_POST['dbname'].';charset=utf8', $_POST['username'], $_POST['password']);
        $bdd = MyPDO::getInstance();
        if($bdd instanceof PDO){
            if(isset($_POST['siteName']) && isset($_POST['adminEmail']) && isset($_POST['nicknameUser']) && isset($_POST['passwordUser'])){
                $_POST['nicknameUser'] = filter_var($_POST['nicknameUser'], FILTER_SANITIZE_SPECIAL_CHARS);

                $erreurs = [];

                if(strlen($_POST['nicknameUser']) < 5 || strlen($_POST['nicknameUser']) > 20)
                    $erreurs[] = "Le nom d'utilisateur pour le site doit faire entre 5 et 20 caractères";

                if(strlen($_POST['passwordUser']) < 8)
                    $erreurs[] = "Le mot de passe doit contenir au moins 8 caractères";

                if(filter_var($_POST['adminEmail'], FILTER_VALIDATE_EMAIL) === false)
                    $erreurs[] = "L'adresse e-mail n'est pas valide";

                if(sizeof($erreurs) == 0){
                    $configIni = fopen('configsql.ini', 'a');
                    $content = <<<TXT
host = "{$_POST['host']}"
dbname = "{$_POST['dbname']}"
username = "{$_POST['username']}"
password = "{$_POST['password']}"
tablePrefix = "{$_POST['tablePrefix']}"
TXT;
                    fputs($configIni, $content);
                    fclose($configIni);
                    $file = fopen("configsql.sql", "a+");

                    $description = isset($_POST['siteDescription']) ? $_POST['siteDescription'] : "";

                    $reqs = "";

                    while($line = fgets($file)){
                        $reqs .= $line . "\n";
                    }

                    fclose($file);

                    $reqs = preg_replace("/prefixTable/", $_POST['tablePrefix'], $reqs);
                    $reqs = preg_replace("/siteNameValue/", $_POST['siteName'], $reqs);
                    $reqs = preg_replace("/siteDescriptionValue/", $description, $reqs);
                    $reqs = preg_replace("/adminEmailValue/", $_POST['adminEmail'], $reqs);

                    $url = explode("/", $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                    unset($url[sizeof($url) - 1]);
                    $url = "http://" . implode("/", $url) . "/";

                    $reqs = preg_replace("/urlSiteValue/", $url, $reqs);
                    $reqs = preg_replace("/nicknameUserValue/", $_POST['nicknameUser'], $reqs);
                    $reqs = preg_replace("/passwordUserValue/", hash("sha256", $_POST['password']), $reqs);

                    $pdo = $bdd->prepare($reqs);
                    $pdo->execute();

                    header("Location: index.php");
                } else {
                    $formConfig = Site::formConfig($_POST, implode("<br>", $erreurs));
                }
            } else {
                $formConfig = Site::formConfig($_POST, implode("<br>", "Certains champs ne sont pas remplis"));
            }

        } elseif($bdd instanceof Exception){
            if($bdd->getCode() == 2002){
                $formConfig = Site::formConfig($_POST, "Hôte inconnu");
            } elseif($bdd->getCode() == 1049) {
                $formConfig = Site::formConfig($_POST, "Base de données inconnue");
            } elseif($bdd->getCode() == 1045) {
                $formConfig = Site::formConfig($_POST, "Identifiants SQL incorrects");
            } else {
                $formConfig = Site::formConfig($_POST, "Erreur de connexion à la base de données inconnue. " . $bdd->getMessage());
            }
        } else {
            $formConfig = Site::formConfig($_POST, "Erreur lors de la configuration");
        }
    } else {
        $formConfig = Site::formConfig($_POST, "Erreur lors de la configuration");
    }
} else {
    $formConfig = Site::formConfig();
}

$html = <<<HTML

<!DOCTYPE html>
<html>
    <head>
        <title>Installation</title>
        <style>
            label, input {
                display: block;
                margin: auto;
            }
            label {
                text-align: center;
            }
            form {
                text-align: center;
                margin: 15px auto 15px auto;
                width: 40%;
            }
            fieldset{
                margin-bottom: 15px;
            }
        </style>
    </head>
    <body>
        $formConfig
    </body>
</html>

HTML;

echo $html;
