<?php
/**
 *
 * Code skeleton generated by dia-uml2php5 plugin
 * written by KDO kdo@zpmag.com
 */

class User {

	/**
	 * ID de l'utilisateur
	 * @var int
	 * @access protected
	 */
	protected  $idUser;

	/**
	 * Pseudo de l'utilisateur
	 * @var String
	 * @access protected
	 */
	protected  $nicknameUser;

	/**
	 * E-mail de l'utilisateur
	 * @var String
	 * @access protected
	 */
	protected  $emailUser;

	protected $validUser;

	protected $resetPasswordUser;

	protected $deleteUser;

	/**
	 * Autorisation écriture d'un article
	 * @var Bool
	 * @access protected
	 */
	protected  $redacArticle;

	/**
	 * Autorisation modification de ses articles
	 * @var Bool
	 * @access protected
	 */
	protected  $editOwnArticle;

	/**
	 * Autorisation suppression de ses articles
	 * @var Bool
	 * @access protected
	 */
	protected  $deleteOwnArticle;

	/**
	 * Autorisation d'édition des commentaires des autres utilisateurs
	 * @var Bool
	 * @access protected
	 */
	protected  $editComment;

	/**
	 * Autorisation de suppression des commentaires des autres utilisateurs
	 * @var Bool
	 * @access protected
	 */
	protected  $deleteComment;

	protected $isAdministrator;


	/**
	 * Fonction __construct privée car PDO s'occupe de l'instanciation
	 * @access private
	 * @return void
	 */

	private  function __construct() {

	}


	/**
	 * Accesseur
	 * @access public
	 * @param String $name Nom de l'attribut
	 * @return Multiple
	 */

	public  function __get($name) {
		if(property_exists(__CLASS__, $name)){
			return $this->$name;
		}
		throw new NotAttributeException("L'attribut {$name} n'existe pas");
	}

	public function __set($name, $value){
        if(property_exists(__CLASS__, $name)){
            $this->$name = $value;
        } else {
            return "Attribut inexistant";
        }
    }

    public static function getUser($idUser) {
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("SELECT * FROM user WHERE idUser = ?");
        $pdo->execute(array($idUser));
        $pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

        $res = $pdo->fetch();

        if(empty($res)){
            return false;
        }

        return $res;
    }

    public static function getUserByNickName($nicknameUser) {
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("SELECT * FROM user WHERE nicknameUser = ?");
        $pdo->execute(array($nicknameUser));
        $pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

        $res = $pdo->fetch();

        if(empty($res)){
            return false;
        }

        return $res;
    }

    public static function getUsers() {
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("SELECT * FROM user");
        $pdo->execute();
        $pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

        $res = $pdo->fetchAll();

        if(empty($res)){
            return false;
        }

        return $res;
    }

    public static function listUsers(){
        $users = self::getUsers();

        $html = <<<HTML
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <tr>
                    <th>ID de l'utilisateur</th>
                    <th>Pseudo</th>
                    <th>E-mail</th>
                    <th>Modfier</th>
                    <th>Supprimer</th>
                </tr>
HTML;

        foreach ($users as $user) {
            $html .= <<<HTML
            <tr>
                <td>{$user->idUser}</td>
                <td>{$user->nicknameUser}</td>
                <td>{$user->emailUser}</td>
                <td><a href=?id={$user->idUser}&a=e>Modifier</a></td>
                <td><a href=?id={$user->idUser}&a=d>Supprimer</a></td>
            </tr>
HTML;
        }

        $html .= "</table></div>";
        return $html;
    }

	public static function formConnection($infos = "", $data = array()){
		$nickname = isset($data['nickname']) ? $data['nickname'] : "";
		$html = <<<HTML
            <div class="row">
                <div class="col-md-2 col-md-offset-5">
        			<form action="" method="post" class="form-horizontal text-center">
        				$infos
        				<input type="text" placeholder="Pseudo" name="nickname" value="$nickname"  class="form-control" required>
        				<input type="password" placeholder="Mot de passe" name="password"  class="form-control" required>
                        <div class="checkbox">
                            <label><input type="checkbox" name="remember">Se souvenir de moi</label>
                        </div>
        				<input type="submit" value="Se connecter" name="formConnection" class="btn btn-default">
        			</form>
                    <div class="text-center"><a href="resetPassword.php">Mot de passe oublié ?</a></div>
                </div>
            </div>
HTML;
		return $html;
	}

    public static function userByCookie($data){
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("SELECT * FROM user WHERE idUser = ?");
        $pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $pdo->execute([$data[0]]);

        $res = $pdo->fetch();

        if(empty($res)){
            setcookie("user", '', time() - 3600, '/', '', false, true);
            return false;
        }

        $key = hash("sha256", $res->nicknameUser . $res->emailUser);

        var_dump($data);
        if($key == $data[1]){
            return User::createFromCookie($data[0]);
        } else {
            setcookie("user", '', time() - 3600, '/', '', false, true);
        }
    }

    public static function genWord($taille = 5){
        $voyelles = array('a','e','i','u','o','y','ou','oi','eu','ai');
        $consonnes = array('z','r','t','p','q','s','d','f','g','h','j','k','l','m','w','x','c','v','b','n','pr','br','cr','bl','pl','ph');
        $mot = '';
        for ($i=0; $i < round($taille/2); $i++) {
            $mot .= $voyelles[array_rand($voyelles)];
            $mot .= $consonnes[array_rand($consonnes)];
        }
        return substr($mot, 0, $taille);
    }

    public static function captcha(){
        $word = User::genWord();

        $_SESSION['captcha'] = $word;
        $long = strlen($word) * 11;
        $larg = 30;
        $img = imagecreate($long, $larg);
        $blanc = imagecolorallocate($img, 255, 255, 255);
        $noir = imagecolorallocate($img, 0, 0, 0);
        imagerectangle($img, 1, 1, $long-1, $larg-1, $noir);

        for ($i=0; $i < strlen($word); $i++) {
            $color = User::colorRand($img, 0, 150);
            imagestring($img, 7, 5+$i*9, 4, $word[$i], $color);
        }
        for ($i=0; $i < 3; $i++) {
            $color = User::colorRand($img, 151, 255);
            imageline($img, 1, mt_rand(1,10), $long, mt_rand(1,19), $color);
        }
        imagepng($img);
        imagedestroy($img);
    }

    public static function colorRand($img, $limitMin, $limiteMax){
        return imagecolorallocate($img, mt_rand($limitMin, $limiteMax), mt_rand($limitMin, $limiteMax), mt_rand($limitMin, $limiteMax));
    }

    public static function createFromCookie($idUser){
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("SELECT idUser, nicknameUser, emailUser, validUser, redacArticle, editOwnArticle, deleteOwnArticle, editComment, deleteComment, isAdministrator FROM user WHERE idUser = ?");
        $pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
        $pdo->execute([$idUser]);
        $res = $pdo->fetch();

        if($res->isAdministrator){
            $res = new Administrator($res);
        }
        setcookie("user", $res->idUser . '----' . hash("sha256", $res->nicknameUser . $res->emailUser), time() + 3600 * 24 * 15, '/', '', false, true);
        self::startSession();
        $_SESSION['connected'] = true;
        return $res;
    }


	/**
	 * Vérifie l'existence de l'utilisateur et retourne une instance de User s'il existe
	 * @access public
	 * @param array $data Contient les informations pour se connecter
	 * @return User
	 */

	public static  function createFromAuth($data) {
		$pdo = MyPDO::getInstance();
		if(empty($data['nickname']) || empty($data['password'])){
			return false;
		}
		$login = $pdo->prepare( 'SELECT idUser, nicknameUser, emailUser, validUser, redacArticle, editOwnArticle, deleteOwnArticle, editComment, deleteComment, isAdministrator
								FROM user
								WHERE nicknameUser = ? AND passwordUser = ?');
        $login->setFetchMode(PDO::FETCH_CLASS, __CLASS__);
		$login->execute(array($data['nickname'], hash("sha256", $data['password'])));
		$res = $login->fetch();
		if(empty($res)){
			return false;
		} elseif(!is_null($res->validUser)){
			return "Votre compte n'a pas été validé";
		} else {
			if($res->isAdministrator){
				$res = new Administrator($res);
			}
            if(isset($data['remember'])){
                setcookie("user", $res->idUser . '----' . hash("sha256", $res->nicknameUser . $res->emailUser), time() + 3600 * 24 * 15, '/', '', false, true);
            }
			self::startSession();
			$_SESSION['connected'] = true;
			return $res;
		}
	}


	/**
	 * Démarre une session si ce n'est pas encore fait
	 * @access public
	 * @return void
	 */

	public static  function startSession() {
		/*if(headers_sent()){
			throw new Exception("Vous êtes déjà connecté");
		}*/
		if(!session_id()){
			session_start();
		}
	}


	/**
	 * Linéarise l'utilisateur en l'enregistrant dans une variable de session
	 * @access public
	 * @return void
	 */

	public  function saveIntoSession() {
		self::startSession();
		$_SESSION['user'] = $this;
	}


	/**
	 * Délinéarisation de l'utilisateur
	 * @access public
	 * @return User
	 */

	public static  function createFromSession() {
		self::startSession();
		if (isset($_SESSION['user'])){
			$u = $_SESSION['user'];
			return $u;
		}
	}


	/**
	 * Vérifie si l'utilisateur est connecté
	 * @access public
	 * @return Bool
	 */

	public static  function isConnected() {
		try{
			self::startSession();
			if (isset($_SESSION['connected']) && $_SESSION['connected']) {
				return true;
			}
		} catch(SessionException $e){
			return "Échec d'authentification&nbsp;: {$e->getMessage()}";
		}
	}

	/**
	 * Déconnecte l'utilisateur
	 * @access public
	 * @return void
	 */

	public static  function logout() {
		self::startSession();
		session_destroy();
		unset($_SESSION);
        setcookie("user", '', time() - 3600, '/', '', false, true);
	}


	public static function formAddUser($infos = array(), $data = null){
		$nickname = isset($data['nickname']) ? $data['nickname'] : "";
		$email = isset($data['email']) ? $data['email'] : "";

		$displayInfos = "";
		if(sizeof($infos) > 0){
			$displayInfos = "<div>" . implode("<br>", $infos) . "</div>";
		}
		$html = <<<HTML
            <div class="row">
                <div class="col-md-6 col-sm-offset-3">
        			<form action="" method="post" class="form-horizontal text-center">
        				$displayInfos
                        <div class="form-group">
            			 	<div class="col-md-4 col-md-offset-4"><input type="text" placeholder="Pseudo" name="nickname" value="$nickname" pattern=".{5,20}"  class="form-control" required></div>
            			 	<div class="col-md-4 col-md-offset-4"><input type="password" placeholder="Mot de passe" name="password" pattern=".{8,}"  class="form-control" required></div>
            			 	<div class="col-md-4 col-md-offset-4"><input type="email" placeholder="Adresse e-mail" name="email" value="$email"  class="form-control" required></div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <label for="captcha" class="control-label col-md-6 col-md-offset-2">Saisissez les lettres de l'image: </label>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-md-offset-3"><input type="text" name="captcha" id="captcha" placeholder="Captcha" required></div>
                                <div class="col-md-2"><img src="captcha.php" alt="captcha"></div>
                            </div>
                        </div>
        			 	<input type="submit" value="S'inscrire" name="formAdd" class="btn btn-default">
        			</form>
                </div>
            </div>
HTML;
		return $html;
	}

	/**
	 * Inscrit un utilisateur
	 * @access public
	 * @param array $data Contient les informations pour l'inscription
	 * @return void
	 */

	public static  function addUser($data) {
		if(!isset($data['nickname']) || empty($data['nickname']) || !isset($data['password']) || empty($data['password']) || !isset($data['email']) || empty($data['email']) || !isset($data['captcha']) || empty($data['captcha']))
			return "Tous les champs sont requis";

		$erreurs = array();

        if($data['captcha'] != $_SESSION['captcha'])
            $erreurs[] = "Le captcha est incorrect";

		$data['nickname'] = filter_var($data['nickname'], FILTER_SANITIZE_SPECIAL_CHARS);

		if(strlen($data['nickname']) < 5 || strlen($data['nickname']) > 20)
			$erreurs[] = "Le pseudo doit faire entre 5 et 20 caractères";

		if(strlen($data['password']) < 8)
			$erreurs[] = "Le mot de passe doit contenir au moins 8 caractères";

		if(filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false)
		    $erreurs[] = "L'adresse e-mail n'est pas valide";

		if(sizeof($erreurs) > 0)
			return $erreurs;

		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM user WHERE nicknameUser = ?");
		$pdo->execute(array($data['nickname']));
		$pseudo = $pdo->fetch();

		if($pseudo)
			$erreurs[] = "Le pseudo est déjà utilisé";

		$pdo = $bdd->prepare("SELECT * FROM user WHERE emailUser = ?");
		$pdo->execute(array($data['email']));
		$email = $pdo->fetch();

		if($email)
			$erreurs[] = "L'adresse e-mail est déjà utilisée";

		if(sizeof($erreurs) > 0)
			return $erreurs;

        $validCode = md5(uniqid());
		$pdo = $bdd->prepare("INSERT INTO USER VALUES(NULL, ?, ?, ?, ?, NULL, NULL, 0, 0, 0, 0, 0, 0)");
		$pdo->execute(array($data['nickname'], $data['email'], hash('sha256', $data['password']), $validCode));

        self::validMail($data['email'], $validCode);

		return true;
	}

	public static function validMail($email, $validCode){

        $site = Site::getOptions();
		$boundary = "-----=".md5(rand());

    	$header = "From: " . $site['adminEmail'] . "\n";
    	$header.= "Reply-to: " . $site['adminEmail'] ."\n";
    	$header.= "MIME-Version: 1.0\n";
    	$header.= "Content-Type: multipart/alternative;\n boundary=" . $boundary . "\n";

    	$message = "\n--" . $boundary . "\n";
    	$message .= "Content-Type: text/html; charset=\"ISO-8859-1\"\n";
		$message .= "Content-Transfer-Encoding: 8bit\n";
        $message .= "\nBienvenue sur " . $site['siteName'] . "\n<br><br>";
        $message .= "\nPour valider votre inscription, <a href='" . $site['urlSite'] . "verif.php?action=valid&email=" . $email . "&code=" . $validCode . "'>cliquez-ici</a>\n<br><br>";
        $message .= "\nSi vous ne pouvez pas cliquer, copiez/collez le lien suivant: " . $site['urlSite'] . "verif.php?action=valid&email=" . $email . "&code=" . $validCode . "\n\n";
		$message .= "\n--" . $boundary . "--\n";
		return mail($email, "[" . $site['siteName'] . "] Validation de l'e-mail", $message, $header);
	}

    public static function verifCode($data){
        $bdd = MyPDO::getInstance();

        if($data['action'] == "valid"){
        	$code = "validUser";
        } elseif($data['action'] == "resetPW"){
        	$code = "resetPasswordUser";
        } elseif($data['action'] == "delete") {
        	$code = "deleteUser";
        } else {
        	return false;
        }
        $pdo = $bdd->prepare("SELECT idUser FROM user WHERE emailUser = ? AND $code = ?");
        $pdo->execute(array($data['email'], $data['code']));
        $res = $pdo->fetch();

        if(empty($res)){
            return false;
        } else {
        	if($code = "validUser"){
	            $pdo = $bdd->prepare("UPDATE user SET $code = null WHERE idUser = ?");
	            $pdo->execute(array($res['idUser']));
	        }
	        return true;
        }
    }

    public static function formResetPasswordEmail($email = ""){
    	$info = !empty($email) ? "L'adresse e-mail n'est pas valide" : "";
    	$html = <<<HTML
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
            		<form action="" method="post" class="form-horizontal text-center">
            			$info
            			<p>Veuillez saisir votre adresse e-mail.</p>
                        <p>Un lien permettant de créer un nouveau mot de passe vous sera envoyé par e-mail.</p>
            			<div class="row">
                            <div class="col-md-4 col-md-offset-4">
                                <input type="email" name="email" value="$email" class="form-control" placeholder="Adresse e-mail" required>
                            </div>
                        </div>
            			<input type="submit" name="formResetPasswordEmail" class="btn btn-default">
            		</form>
                </div>
            </div>
HTML;
		return $html;
    }


    public static function resetPasswordEmail($email){

        $resetCode = md5(uniqid());
        $bdd = MyPDO::getInstance();
        $pdo = $bdd->prepare("SELECT nicknameUser FROM user WHERE emailUser = ?");
        $pdo->execute(array($email));
        $res = $pdo->fetch();
        if(empty($res)){
        	return false;
        }

        $pdo = $bdd->prepare("UPDATE user SET resetPasswordUser = ? WHERE nicknameUser = ?");
        $pdo->execute(array($resetCode, $res['nicknameUser']));

        $nicknameUser = $res['nicknameUser'];

        $site = Site::getOptions();
		$boundary = "-----=".md5(rand());

    	$header = "From: " . $site['adminEmail'] . "\n";
    	$header.= "Reply-to: " . $site['adminEmail'] ."\n";
    	$header.= "MIME-Version: 1.0\n";
    	$header.= "Content-Type: multipart/alternative;\n boundary=" . $boundary . "\n";

    	$message = "\n--" . $boundary . "\n";
    	$message .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$message .= "Content-Transfer-Encoding: 8bit\n";
        $message .= "\nBonjour, \n<br>";
        $message .= "\nQuelqu’un a demandé la réinitialisation du mot de passe pour le compte suivant : $nicknameUser\n\n<br><br>";
        $message .= "\nS’il s’agit d’une erreur, ignorez ce message et la demande ne sera pas prise en compte.\n\n<br><br>";
        $message .= "\nPour renouveler votre mot de passe, <a href='" . $site['urlSite'] . "verif.php?action=resetPW&email=" . $email . "&code=" . $resetCode . "'>cliquez-ici</a>\n<br><br>";
        $message .= "\nSi vous ne pouvez pas cliquer, copiez/collez le lien suivant: " . $site['urlSite'] . "verif.php?action=resetPW&email=" . $email . "&code=" . $resetCode . "\n\n";
		$message .= "\n--" . $boundary . "--\n";
		return mail($email, "[" . $site['siteName'] . "] Oubli de mot de passe", $message, $header);
	}

	public static function formResetPassword($info = ""){
		if(isset($_GET['email']) && isset($_GET['code'])){
			$email = $_GET["email"];
			$code = $_GET["code"];
			$html = <<<HTML
            <div class="row">
                <div class="col-md-2 col-md-offset-5">
        			<form action="" method="post" class="form-horizontal text-center">
        				$info
        				<input type="password" name="password" placeholder="Mot de passe (min: 8 car.)" pattern=".{8,}" class="form-control" required>
        				<input type="submit" name="formResetPassword" value="Modifier" class="btn btn-default">
        				<input type="hidden" name="email" value="$email">
        				<input type="hidden" name="code" value="$code">
        			</form>
                </div>
            </div>
HTML;

			return $html;
		} else {
			return false;
		}
	}

	public static function updateResetPassword($data){
		if(strlen($data['password']) < 8){
			return false;
		}
		$bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("UPDATE user SET passwordUser = ?, resetPasswordUser = null WHERE emailUser = ?");
        $pdo->execute(array(hash('sha256', $data['password']), $data['email']));
        return true;
	}

	public function formEditEmail($email = "", $info = ""){
		$email = empty($email) ? $this->emailUser : $email;
		$html = <<<HTML
            <div class="row">
                <div class="col-sm-offset-5 col-sm-2 text-center">
        			<form action="" method="post" class="form-horizontal">
        				$info
        			 	<input type="email" placeholder="E-mail" name="email" value="$email" class="form-control" required>
        			 	<input type="submit" value="Modifier" name="formEditEmail" class="btn btn-default">
        			</form>
                </div>
            </div>
HTML;
		return $html;
	}

	/**
	 * Editer son profil
	 * @access public
	 * @param array $data Contient les nouvelles informations du profil
	 * @return void
	 */

	public  function editEmail($email) {
		if($this->emailUser == $email)
			return true;

		if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
		    return false;

		$bdd = MyPDO::getInstance();
		$pdo = $bdd->prepare("SELECT emailUser FROM user WHERE emailUser = ?");
		$pdo->execute(array($email));
		$res = $pdo->fetch();
		if($res){
			return -1;
		}
		$pdo = $bdd->prepare("UPDATE user SET emailUser = ? WHERE idUser = ?");
		$pdo->execute(array($email, $this->idUser));
        $this->emailUser = $email;
        $_SESSION['user']->emailUser = $this->emailUser;
		return true;
	}

	public function formDeleteUser($info = ""){
		$html = <<<HTML
            <div class="row">
                <div class="col-sm-offset-4 col-sm-4">
        			<form action="" method="post" class="form-horizontal text-center">
        				$info
                        <p>Supprimer mon compte:</p>
        				<div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="password" name="password" placeholder="Mot de passe" class="form-control" required>
                            </div>
                        </div>
        				<input type="submit" name="formDeleteUser" value="Supprimer mon compte" class="btn btn-default">
        			</form>
                </div>
            </div>
HTML;
		return $html;
	}

	public function deleteUser($password){
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT passwordUser FROM user WHERE idUser = ? AND passwordUser = ?");
		$pdo->execute(array($this->idUser, hash("sha256", $password)));
		$res = $pdo->fetch();

		if(empty($res)){
			return false;
		}

		$this->deleteUser = md5(uniqid());
		$_SESSION['user']->deleteUser = $this->deleteUser;
		$pdo = $bdd->prepare("UPDATE user SET deleteUser = ? WHERE idUser = ?");
		$pdo->execute(array($this->deleteUser, $this->idUser));

		return true;
	}

	public function deleteUserEmail(){
        $site = Site::getOptions();
		$boundary = "-----=".md5(rand());

    	$header = "From: " . $site['adminEmail'] . "\n";
    	$header.= "Reply-to: " . $site['adminEmail'] ."\n";
    	$header.= "MIME-Version: 1.0\n";
    	$header.= "Content-Type: multipart/alternative;\n boundary=" . $boundary . "\n";

    	$message = "\n--" . $boundary . "\n";
    	$message .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$message .= "Content-Transfer-Encoding: 8bit\n";
        $message .= "\nBonjour, \n<br>";
        $message .= "\nQuelqu’un a demandé la suppression du compte suivant : $this->nicknameUser\n\n<br><br>";
        $message .= "\nS’il s’agit d’une erreur, ignorez ce message et la demande ne sera pas prise en compte.\n\n<br><br>";
        $message .= "\nPour supprimer votre compte, <a href='" . $site['urlSite'] . "verif.php?action=delete&email=" . $this->emailUser . "&code=" . $this->deleteUser . "'>cliquez-ici</a>\n<br><br>";
        $message .= "\nSi vous ne pouvez pas cliquer, copiez/collez le lien suivant: " . $site['urlSite'] . "verif.php?action=delete&email=" . $this->emailUser . "&code=" . $this->deleteUser . "\n\n";
		$message .= "\n--" . $boundary . "--\n";
		return mail($this->emailUser, "[" . $site['siteName'] . "] Suppression de compte", $message, $header);

	}

	public static function validDeleteUser($email){
		$bdd = MyPDO::getInstance();
		$pdo = $bdd->prepare("SELECT isAdministrator FROM user WHERE emailUser = ?");
		$pdo->execute(array($email));
		$res = $pdo->fetch();

		if($res['isAdministrator']){
			return false;
		}

		$pdo = $bdd->prepare("DELETE FROM user WHERE emailUser = ?");
		$pdo->execute(array($email));

		return true;
	}

    public function formSendPM($data = array(), $info = ""){
        $receiver = isset($data['receiver']) ? $data['receiver'] : "";
        $title = isset($data['title']) ? $data['title'] : "";
        $content = isset($data['content']) ? $data['content'] : "";

        $html = <<<HTML
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="" method="post" class="form-horizontal text-center">
                    $info
                    <div class="col-md-6 col-md-offset-3">
                        <input type="text" name="receiver" value="$receiver" placeholder="Destinataire" class="form-control" required>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <input type="text" name="title" value="$title" placeholder="Objet" class="form-control" required>
                    </div>
                    <textarea name="content" placeholder="Message" class="form-control" rows="3" required>$content</textarea>
                    <input type="submit" name="formSendPM" class="btn btn-default">
                </form>
            </div>
        </div>
HTML;

        return $html;
    }

    public function formReplyPM($data = array(), $info = ""){
        $title = isset($data['title']) ? $data['title'] : "RE: " . $data['defaultTitle'];
        $content = isset($data['content']) ? $data['content'] : "";

        $html = <<<HTML
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <form action="" method="post" class="form-horizontal text-center">
                    $info
                    <div class="col-md-6 col-md-offset-3">
                        <input type="text" name="title" value="$title" placeholder="Objet" class="form-control" required>
                    </div>
                    <textarea name="content" placeholder="Message" class="form-control" rows="3" required>$content</textarea>
                    <input type="submit" name="formReplyPM" value="Répondre" class="btn btn-default">
                </form>
            </div>
        </div>
HTML;

        return $html;
    }

	public static function formContact($data = array(), $info = ""){
        $subject = isset($data['subject']) ? $data['subject'] : "";
        $email = isset($data['email']) ? $data['email'] : "";
		$message = isset($data['message']) ? $data['message'] : "";

		$html = <<<HTML
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
        		<form action="" method="post" class="form-horizontal text-center">
        			$info
                    <div class="col-md-6 col-md-offset-3">
                        <input type="text" name="subject" placeholder="Objet du message" value="$subject" class="form-control" required>
                    </div>
                    <div class="col-md-6 col-md-offset-3">
                        <input type="email" name="email" placeholder="Adresse e-mail" value="$email" class="form-control" required>
                    </div>
        			<textarea name="message" placeholder="Message" class="form-control" rows="3" required>$message</textarea>
        			<input type="submit" name="formContact" class="btn btn-default">
        		</form>
            </div>
        </div>
HTML;

		return $html;
	}

	public static function contact($data){
        if(filter_var($data['email'], FILTER_VALIDATE_EMAIL) === false){
            return false;
        }
        $site = Site::getOptions();
        $subject = "[" . $site['siteName'] . "] " . $data['subject'];
        $boundary = "-----=".md5(rand());

        $header = "From: " . $data['email'] . "\n";
        $header.= "Reply-to: " . $data['email'] ."\n";
        $header.= "MIME-Version: 1.0\n";
        $header.= "Content-Type: multipart/alternative;\n boundary=" . $boundary . "\n";

        return mail($site['adminEmail'], $subject , $data['message'], $header);
	}

}
?>
