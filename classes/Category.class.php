<?php
/**
 *
 * Code skeleton generated by dia-uml2php5 plugin
 * written by KDO kdo@zpmag.com
 */

class Category {

	/**
	 *
	 * @var int
	 * @access public
	 */
	public  $idCategory;

	/**
	 *
	 * @var String
	 * @access public
	 */
	public  $lblCategory;

	/**
	 *
	 * @var int
	 * @access public
	 */
	public  $idCategoryParent;

	/**
	 * @var array
	 */
	public $categoryChildren;


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
	 * @param int $idCategory
	 * @return Category
	 */

	public static function getCategory($idCategory) {
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM category WHERE idCategory = ?");
		$pdo->execute(array($idCategory));
		$pdo->setFetchMode(PDO::FETCH_CLASS, __CLASS__);

		$res = $pdo->fetch();

		if(empty($res)){
			return false;
		}

		$res->getCategorysChildren();

		return $res;
	}


	public static function getCategorys($parent = false) {
		$bdd = MyPDO::getInstance();

		if($parent){
			$req = " WHERE idCategoryParent IS NULL";
		} else {
			$req = "";
		}

		$pdo = $bdd->prepare("SELECT * FROM category" . $req);
		$pdo->execute();

		$res = $pdo->fetchAll(PDO::FETCH_CLASS, __CLASS__);

		if(empty($res)){
			return false;
		}

		foreach ($res as $category) {
			$category->getCategorysChildren();
			$categorys[] = $category;
		}

		return $categorys;
	}


	/**
	 * @access public
	 * @return Category()
	 */

	public function getCategorysChildren() {
		$bdd = MyPDO::getInstance();

		$pdo = $bdd->prepare("SELECT * FROM category WHERE idCategoryParent = ?");
		$pdo->execute(array($this->idCategory));

		$res = $pdo->fetchAll(PDO::FETCH_CLASS, __CLASS__);

		if(empty($res)){
			return false;
		}

		foreach ($res as $category) {
			$category->getCategorysChildren();
			$categorys[] = $category;
		}

		if(!isset($categorys)){
			return array();
		}

		$this->categoryChildren = $categorys;
	}

	public function getBreadcrumb(){
		$breadcrumb = "<a href='categories.php?id=" . $this->idCategory . "'>" . $this->lblCategory . '</a>';
		if(!is_null($this->idCategoryParent)){
			$categoryParent = Category::getCategory($this->idCategoryParent);
			$breadcrumb = "<li>" . $categoryParent->getBreadcrumb() . "</li><li class='active'>" . $breadcrumb . "</li>";
		}

		return $breadcrumb;
	}

	public static function formAddCategory($data = array(), $info = ""){

		$categorys = Category::getCategorys();

		$lblCategory = isset($data['lblCategory']) ? $data['lblCategory'] : "";
		$categoryParent = isset($data['categoryParent']) ? $data['categoryParent'] : "";

		$select = "<select name='category'  class='form-control'>";
		$select .= "<option value=''>Aucune catégorie parent</option>";

		if($categorys){
			foreach ($categorys as $category) {
				if($category->lblCategory == $categoryParent){
					$select .= "<option value=" . $category->idCategory . " selected>" . $category->lblCategory . "</option>";
				} else {
					$select .= "<option value=" . $category->idCategory . ">" . $category->lblCategory . "</option>";
				}
			}
		}

		$select .= "</select>";

		$html = <<<HTML
		<div class="col-md-4 col-md-offset-4 text-center">
			<form action="" method="post" class="form-horizontal">
				$info
				<input type="text" name="lblCategory" placeholder="Nom de la catégorie" pattern=".{1,}" class="form-control">
				$select
				<input type="submit" name="formAddCategory" value="Ajouter" class="btn btn-default">
			</form>
		</div>
HTML;
		return $html;
	}

	public static function addCategory($data){
		if(strlen($data['lblCategory']) == 0){
			return false;
		}

		if($data['category'] == "" || !Category::getCategory($data['category'])){
			$data['category'] = null;
		}

		$bdd = MyPDO::getInstance();
		$pdo = $bdd->prepare("INSERT INTO category VALUES(NULL, ?, ?)");
		$pdo->execute(array($data['lblCategory'], $data['category']));

		return true;
	}

	public function getIdChildren(){
			if(!is_null($this->categoryChildren)){
				foreach($this->categoryChildren as $categoryChild){
					$list[] = $categoryChild->idCategory;
					if(!is_null($categoryChild->categoryChildren)){
						$children = $categoryChild->getIdChildren();
						if(is_array($children)){
							foreach ($children as $child) {
								$list[] = $child;
							}
						} else {
							$lis[] = $children;
						}
					}
				}
				return $list;
			}
	}

	public function formEditCategory($data = array(), $info = ""){
		$categorys = Category::getCategorys();

		$lblCategory = isset($data['lblCategory']) ? $data['lblCategory'] : $this->lblCategory;
		$categoryParent = isset($data['categoryParent']) ? $data['categoryParent'] : "";

		$select = "<select name='category' class='form-control'>";
		$select .= "<option value=''>Aucune catégorie parent</option>";

		$listChildren = array();
		$list = array();
		foreach ($categorys as $category) {
			if($category->idCategory !== $this->idCategory){
				if($category->idCategoryParent == $this->idCategory){
					if(!is_null($category->categoryChildren))
						$listChildren = $category->getIdChildren();
				}

				if(!empty($listChildren)){
					foreach ($listChildren as $value) {
						if(is_array($value)){
							foreach ($value as $id) {
								$list[] = $id;
							}
						} else {
							$list[] = $value;
						}
					}
				}


				if(!in_array($category->idCategory, $list) && $category->idCategoryParent !== $this->idCategory){
					if($this->idCategoryParent == $category->idCategory){
						$select .= "<option value=" . $category->idCategory . " selected>" . $category->lblCategory . "</option>";
					} else {
						$select .= "<option value=" . $category->idCategory . ">" . $category->lblCategory . "</option>";
					}
				}
			}
		}

		$select .= "</select>";

		$html = <<<HTML
		<div class="col-md-4 col-md-offset-4 text-center">
			<form action="" method="post" class="form-horizontal">
				$info
				<input type="text" name="lblCategory" placeholder="Nom de la catégorie" value=$lblCategory pattern=".{1,}" class="form-control">
				$select
				<input type="submit" name="formEditCategory" value="Modifier" class="btn btn-default">
			</form>
		</div>
HTML;
		return $html;
	}

	public function editCategory($data){
		if(strlen($data['lblCategory']) == 0){
			return false;
		}

		if($data['category'] == "" || !Category::getCategory($data['category'])){
			$data['category'] = null;
		}

		$bdd = MyPDO::getInstance();
		$pdo = $bdd->prepare("UPDATE category SET lblCategory = ?, idCategoryParent = ? WHERE idCategory = ?");
		$pdo->execute(array($data['lblCategory'], $data['category'], $this->idCategory));

		return true;
	}

	public function formDeleteCategory($info = ""){
		$html = <<<HTML
		<div class="row">
			<div class="col-md-8 col-md-offset-2 text-center">
				En supprimant la catégorie {$this->lblCategory}, toutes les catégories enfants ainsi que leurs articles seront supprimés
			</div>
		</div>
		<div class="row">
			<div class="col-md-4 col-md-offset-4 text-center">
		        <form action="" method="post" class="form-inline">
		            <p>Supprimer la categorie {$this->lblCategory} ?</p>
		            <input type="submit" name="formDeleteCategory" value="Confirmer" class="btn btn-primary">
		            <input type="submit" name="cancelDeleteCategory" value="Annuler" class="btn btn-danger">
		        </form>
		    </div>
		</div>
HTML;
        return $html;
	}

	public function deleteCategory(){
        $bdd = MyPDO::getInstance();

        $pdo = $bdd->prepare("DELETE FROM category WHERE idCategory = ?");
        $pdo->execute(array($this->idCategory));

        return true;
	}

	public static function displayCategorys(){
		$categorys = Category::getCategorys(true);
		$html = <<<HTML
		<a href="?a=a">Ajouter une catégorie</a>
        <div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th>Nom de la catégorie</th>
				<th>Catégorie parent</th>
				<th>Modifier</th>
				<th>Supprimer</th>
			</tr>
HTML;
		if(!$categorys){
            $html .= <<<HTML
            </table></div>
            <p>Aucune categorie à afficher</p>
HTML;
        } else {
			foreach ($categorys as $category) {
	            if(is_null($category->idCategoryParent)){
	            	$lblCategoryParent = "Aucun parent";
	            } else {
	            	$categoryParent = Category::getCategory($category->idCategoryParent);
	            	$lblCategoryParent = $categoryParent->lblCategory;
	            }

				$html .= <<<HTML
				<tr>
					<td>{$category->lblCategory}</td>
					<td>$lblCategoryParent</td>
					<td><a href="?id={$category->idCategory}&a=e">Modifier</a></td>
					<td><a href="?id={$category->idCategory}&a=d">Supprimer</a></td>
				</tr>
HTML;
				if(!is_null($category->categoryChildren))
					$html .= $category->displayCategorysChildren();
			}

			$html .= "</table></div>";
		}

		return $html;
	}

	public function displayCategorysChildren(){
		$html = "";
		foreach ($this->categoryChildren as $categoryChild) {
			$html .= <<<HTML
			<tr>
				<td>{$categoryChild->lblCategory}</td>
				<td>{$this->lblCategory}</td>
				<td><a href="?id={$categoryChild->idCategory}&a=e">Modifier</a></td>
				<td><a href="?id={$categoryChild->idCategory}&a=d">Supprimer</a></td>
			</tr>
HTML;
			if(!is_null($categoryChild->categoryChildren))
				$html .= $categoryChild->displayCategorysChildren();

		}

		return $html;
	}

	public static function displayMenuCategorys(){
		$categorys = Category::getCategorys(true);
		$html = "";

		if($categorys){

			foreach ($categorys as $category) {
				if(!is_null($category->categoryChildren)){
					$html .= <<<HTML
					<li class="dropdown-parent">
						<a href='categories.php?id={$category->idCategory}' class="dropdown-toggle">{$category->lblCategory} <span class="caret"></span></a>
HTML;
					$html .= "<ul class='dropdown-menu multi-level'>" . $category->displayMenuCategorysChildren() . "</ul></li>";
				} else{
					$html .= "<li><a href='categories.php?id=" . $category->idCategory . "'>" . $category->lblCategory . "</a></li>";
				}

			}

		}

		return $html;
	}

	public function displayMenuCategorysChildren(){
		$html = "";

		foreach ($this->categoryChildren as $categoryChild) {
			if(!is_null($categoryChild->categoryChildren)){
				$html .= <<<HTML
				<li class="dropdown-submenu">
					<a href='categories.php?id={$categoryChild->idCategory}' class="dropdown-toggle">{$categoryChild->lblCategory} <span class="caretright"></span></a>
HTML;
				$html .= "<ul class='dropdown-menu'>" . $categoryChild->displayMenuCategorysChildren() . "</ul></li>";
			} else{
				$html .= "<li><a href='categories.php?id=" . $categoryChild->idCategory . "'>" . $categoryChild->lblCategory . "</a></li>";
			}
		}

		return $html;
	}

	public static function displayArticleCategorys($idCategory = null){
		$categorys = Category::getCategorys(true);
		$html = "<div class='col-md-2 col-md-offset-5'><ul class='list-group text-center'>";

		foreach ($categorys as $category) {
			$checked = "";
			if($category->idCategory == $idCategory){
				$checked = "checked";
			}
			$html .= <<<HTML
				<div class="radio">
					<li class="list-group-item">
						<label>
							<input type='radio' name='category' value="{$category->idCategory}" $checked required>{$category->lblCategory}
						</label>
					</li>
				</div>
HTML;
			if(!is_null($category->categoryChildren))
				$html .= $category->displayArticleCategorysChildren($idCategory);

		}

		$html .= "</ul></div>";

		return $html;
	}

	public function displayArticleCategorysChildren($idCategory = null){
		$html = "<ul class='list-group'>";

		foreach ($this->categoryChildren as $categoryChild) {
			$checked = "";
			if($categoryChild->idCategory == $idCategory){
				$checked = "checked";
			}
			$html .= <<<HTML
				<div class="radio">
					<li class="list-group-item">
						<label>
							<input type='radio' name='category' value="{$categoryChild->idCategory}" $checked required> {$categoryChild->lblCategory}
						</label>
					</li>
				</div>
HTML;
			if(!is_null($categoryChild->categoryChildren))
				$html .= $categoryChild->displayArticleCategorysChildren($idCategory);
		}

		$html .= "</ul>";

		return $html;
	}

	public static function displayForum($idCategory = null)
	{
		if(is_null($idCategory)){
			$categories = Category::getCategorys(true);
			$articles = false;
		} else {
			$categoryParent = Category::getCategory($idCategory);
			$categories = $categoryParent->categoryChildren;
			$articles = Article::getArticlesPerCategory(array($categoryParent->idCategory));
		}

		$html = "";

		if(!is_null($categories)){
			$html .= <<<HTML
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
			        <div class="table-responsive">
			        	<table class="table table-bordered table-striped">
							<tr>
								<th>Catégorie</th>
							</tr>
HTML;
			foreach ($categories as $category) {
				$html .= <<<HTML
				<tr>
					<td><a href="?idC={$category->idCategory}">{$category->lblCategory}</a></td>
				</tr>
HTML;
			}

			$html .= "</table></div></div></div>";
		}

		if($articles){
			$html .= <<<HTML
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
		        <div class="table-responsive">
		        	<table class="table table-bordered table-striped">
						<tr>
							<th>Articles</th>
						</tr>
HTML;
			foreach ($articles as $article) {
				$html .= <<<HTML
				<tr>
					<td><a href="?idA={$article->idArticle}">{$article->titleArticle}</a></td>
				</tr>
HTML;
			}

			$html .= "</table></div></div></div>";
		}


		return $html;
	}
}
?>
