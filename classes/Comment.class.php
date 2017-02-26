<?php
/**
 *
 * Code skeleton generated by dia-uml2php5 plugin
 * written by KDO kdo@zpmag.com
 */

class Comment {

	/**
	 *
	 * @var int
	 * @access public
	 */
	public  $idComment;

	/**
	 *
	 * @var String
	 * @access public
	 */
	public  $contentComment;

	public $datetimeComment;

	/**
	 *
	 * @var int
	 * @access public
	 */
	public  $idUser;

	/**
	 *
	 * @var
	 * @access public
	 */
	public  $idArticle;



	/**
	 * @access private
	 * @return void
	 */

	private  function __construct() {

	}


	/**
	 * @access public
	 * @param String $name
	 * @return Multiple
	 */

	public  function __get($name) {
		if(property_exists(__CLASS__, $name)){
			return $this->$name;
		}
		throw new Exception("L'attribut {$name} n'existe pas");
	}


	/**
	 * @access public
	 * @param int $idComment
	 * @return Comment
	 */

	public static function getComment($idComment) {
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM comment WHERE idComment = ?");
		$pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

		$pdo->execute(array($idComment));

		$res = $pdo->fetch();

		if(empty($res)){
			return false;
		}

		return $res;
	}


	/**
	 * @access public
	 * @return Comment()
	 */

	public static function getComments() {
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM comment");
		$pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

		$pdo->execute();

		$res = $pdo->fetchAll();

		if(empty($res)){
			return false;
		}

		return $res;
	}

	public static function getCommentsPerArticle($idArticle) {
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM comment WHERE idArticle = ?");
		$pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

		$pdo->execute(array($idArticle));

		$res = $pdo->fetchAll();

		if(empty($res)){
			return false;
		}

		return $res;
	}

	public static function displayComments($idArticle){
		$html = '<div>';

		$comments = self::getCommentsPerArticle($idArticle);

		if($comments){
			foreach ($comments as $comment) {
				$contentComment = nl2br($comment->contentComment);
				$html .= <<<HTML
				<p>
					$contentComment
				</p>
HTML;
			}
		} else {
			$html .= "<p>Aucun commentaire</p>";
		}

		$html .= '</div>';

		return $html;
	}

	public static function displayCommentsAdmin($idArticle){
		$html = <<<HTML
		<table>
			<tr>
				<th>Commentaire</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>
HTML;

		$comments = self::getCommentsPerArticle($idArticle);

		if($comments){
			foreach ($comments as $comment) {
				$contentComment = nl2br($comment->contentComment);
				$td = <<<HTML
					<td>$contentComment</td>
HTML;

				if($_SESSION['user']->editComment || $_SESSION['user']->isAdministrator){
					$td .= <<<HTML
					<td><a href="?idA=$idArticle&idC={$comment->idComment}&a=e">Modifier</a></td>
HTML;
				} else {
					$td .= <<<HTML
					<td>Modifier</td>
HTML;
				}

				if($_SESSION['user']->deleteComment || $_SESSION['user']->isAdministrator){
					$td .= <<<HTML
					<td><a href="?idA=$idArticle&idC={$comment->idComment}&a=d">Supprimer</a></td>
HTML;
				} else {
					$td .= <<<HTML
					<td>Supprimer</td>
HTML;
				}

				$html .= <<<HTML
				<tr>
					$td
				</tr>
HTML;
			}
		} else {
			$html .= "<p>Aucun commentaire</p>";
		}

		$html .= '</div>';

		return $html;
	}

	public static function formAddComment($data = array(), $info = "")
	{
		$id = $_GET['idA'];
		$content = isset($data['content']) ? $data['content'] : "";
		$html = <<<HTML
			<p>Rédiger un commentaire:</p>
			<form action="?idA=$id" method="post">
				$info
				<textarea name="content">$content</textarea>
				<input type="submit" name="formAddComment">
			</form>
HTML;

		return $html;
	}

	public static function addComment($data, $idArticle)
	{
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("INSERT INTO comment VALUES(NULL, ?, NOW(), ?, ?)");
		$pdo->execute(array(htmlentities($data['content']), $idArticle, $_SESSION['user']->idUser));

		return true;
	}

	public function formEditComment($data = array(), $info = "")
	{
		$idA = $_GET['idA'];
		$content = isset($data['content']) ? nl2br($data['content']) : nl2br($this->contentComment);
		$html = <<<HTML
			<p>Rédiger un commentaire:</p>
			<form action="?idA=$idA&idC={$this->idComment}&a=e" method="post">
				$info
				<textarea name="content">$content</textarea>
				<input type="submit" name="formEditComment">
			</form>
HTML;

		return $html;
	}

	public  function editComment($data)
	{
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("UPDATE comment SET contentComment = ? WHERE idComment = ?");
		$pdo->execute(array(htmlentities($data['content']), $this->idComment));

		return true;
	}

	public function formDeleteComment()
	{
		$idA = $_GET['idA'];
		$content = isset($data['content']) ? nl2br($data['content']) : nl2br($this->contentComment);
		$html = <<<HTML
			<p>Rédiger un commentaire:</p>
			<form action="?idA=$idA&idC={$this->idComment}&a=d" method="post">
				<p>Supprimer le commentaire ?</p>
				<p>$content</p>
				<input type="submit" name="formDeleteComment" value="Supprimer">
				<input type="submit" name="cancelDeleteComment" value="Annuler">
			</form>
HTML;

		return $html;
	}

	public  function deleteComment()
	{
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("DELETE FROM comment WHERE idComment = ?");
		$pdo->execute(array($this->idComment));

		return true;
	}
}
?>
