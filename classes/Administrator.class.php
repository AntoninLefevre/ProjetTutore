<?php
/**
 * Classe enfant de User
 * Code skeleton generated by dia-uml2php5 plugin
 * written by KDO kdo@zpmag.com
 * @see        User
 */
require_once('User.class.php');
require_once('Category.class.php');

class Administrator extends User {


	public function __construct($user){
		$this->idUser = $user->idUser;
		$this->nicknameUser = $user->nicknameUser;
		$this->emailUser = $user->emailUser;
		$this->validUser = $user->validUser;
		$this->redacArticle = $user->redacArticle;
		$this->editOwnArticle = $user->editOwnArticle;
		$this->deleteOwnArticle = $user->deleteOwnArticle;
		$this->editComment = $user->editComment;
		$this->deleteComment = $user->deleteComment;
		$this->isAdministrator = $user->isAdministrator;
	}

	public static function formOptionsSite($data = array(), $infos = ""){
		if(sizeof($data) == 0)
			$data = Site::getOptions();

		$siteName = $data['siteName'];
		$siteDescription = $data['siteDescription'];
		$adminEmail = $data['adminEmail'];
		$articlesPerPage = $data['articlesPerPage'];
		$commentsPerPage = $data['commentsPerPage'];
		$theme = $data['theme'];

		$listThemes = "<select name='theme' class='form-control'>";

		foreach (scandir('./../style/') as $dir) {
		    if(is_dir("./../style/" . $dir) && $dir != "." && $dir != ".."){
		    	if($dir == $theme){
		    		$selected = " selected";
		    	} else {
		    		$selected = "";
		    	}
		        $listThemes .= <<<HTML
		        <option value="$dir" $selected>$dir</option>
HTML;
		    }
		}

		$listThemes .= "</select>";
		$html = <<<HTML
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<form action="" method="post" class="form-horizontal text-center">
					<div class="form-group">
						<label class="control-label col-md-5">Nom du site: </label>
						<div class="col-md-7">
							<input type="text" name="siteName" placeholder="Nom du site" value="$siteName" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Description: </label>
						<div class="col-md-7">
							<input type="text" name="siteDescription" placeholder="Description du site" value="$siteDescription" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Adresse de contact: </label>
						<div class="col-md-7">
							<input type="text" name="adminEmail" placeholder="E-mail de contact" value="$adminEmail" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Articles par page: </label>
						<div class="col-md-7">
							<input type="number" name="articlesPerPage" placeholder="Nombre d'articles par page" value="$articlesPerPage" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Commentaires par page: </label>
						<div class="col-md-7">
							<input type="number" name="commentsPerPage" placeholder="Nombre de commentaires par page" value="$commentsPerPage" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-md-5">Thème: </label>
						<div class="col-md-7">
							$listThemes
						</div>
					</div>
					<input type="submit" name="formOptionsSite" class="btn btn-default">
				</form>
			</div>
		</div>
HTML;
		return $html;
	}



	public static function editOptionSite($data){
		$bdd = MyPDO::getInstance();

		$keys = array_keys($data);

		$pdo = $bdd->prepare("UPDATE optionsite SET valueOptionSite = ? WHERE nameOptionSite = ?");

		foreach ($keys as $key) {
			$pdo->execute(array($data[$key], $key));
		}

		return true;
	}

	/**
	 * Supprime un utilisateur
	 * @access public
	 * @param int $idUser ID de l'utilisateur à supprimer
	 * @return void
	 */

	/*public  function deleteUser($idUser) {

	}*/

	public static function formEditProfileUser($user, $info = ""){
		$redacArticle = $user->redacArticle ? "checked" : "";
		$editOwnArticle = $user->editOwnArticle ? "checked" : "";
		$deleteOwnArticle = $user->deleteOwnArticle ? "checked" : "";
		$editComment = $user->editComment ? "checked" : "";
		$deleteComment = $user->deleteComment ? "checked" : "";
		$html = <<<HTML
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<form action="" method="post" class="form-horizontal text-center">
					$info
					<input type="text" name="nickname" placeholder="Pseudo" value="{$user->nicknameUser}" class="form-control">
					<input type="text" name="email" placeholder="E-mail" value="{$user->emailUser}" class="form-control">
					<div class="checkbox"><label><input type="checkbox" name="redacArticle" $redacArticle> Rédiger un article</label></div>
					<div class="checkbox"><label><input type="checkbox" name="editOwnArticle" $editOwnArticle> Modifier ses articles</label></div>
					<div class="checkbox"><label><input type="checkbox" name="deleteOwnArticle" $deleteOwnArticle> Supprimer ses articles</label></div>
					<div class="checkbox"><label><input type="checkbox" name="editComment" $editComment></label> Modifier des commentaires</div>
					<div class="checkbox"><label><input type="checkbox" name="deleteComment" $deleteComment>Supprimer des commentaires</label></div>
					<input type="submit" value="Modifier" name="formEditProfileUser" class="btn btn-default">
				</form>
			</div>
		</div>
HTML;

		return $html;
	}

	/**
	 * Modifie les information d'un utilisateur
	 * @access public
	 * @param array $data Contient les nouvelles informations du profil de l'utilisateur
	 * @return void
	 */

	public static function editProfileUser($data, $idUser) {
		$redacArticle = isset($data['redacArticle']) ? 1 : 0;
		$editOwnArticle = isset($data['editOwnArticle']) ? 1 : 0;
		$deleteOwnArticle = isset($data['deleteOwnArticle']) ? 1 : 0;
		$editComment = isset($data['editComment']) ? 1 : 0;
		$deleteComment = isset($data['deleteComment']) ? 1 : 0;

		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("UPDATE user SET nicknameUser = ?, emailUser = ?, redacArticle = ?, editOwnArticle = ?, deleteOwnArticle = ?, editComment = ?, deleteComment = ? WHERE idUser = ?");

		$pdo->execute(array($data['nickname'], $data['email'], $redacArticle, $editOwnArticle, $deleteOwnArticle, $editComment, $deleteComment, $idUser));

		return true;
	}

	public static function formDeleteProfileUser($user, $info = ""){
		$html = <<<HTML
		<div class="row">
			<div class="col-md-4 col-md-offset-4">
				<form action="" method="post" class="form-horizontal text-center">
					$info
					<p>Voulez-vous vraiment supprimer {$user->nicknameUser} ?</p>
					<input type="submit" value="Supprimer" name="formDeleteProfileUser" class="btn btn-primary">
					<input type="submit" value="Annuler" name="cancelDeleteProfileUser" class="btn btn-danger">
				</form>
			</div>
		</div>
HTML;

		return $html;
	}

	public static function deleteProfileUser($idUser){
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("DELETE FROM user WHERE idUser = ?");
		$pdo->execute(array($idUser));

		return true;
	}

}
?>
