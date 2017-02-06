<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(isset($_POST['formAddCategory'])){
    $res = Category::addCategory($_POST);
    if(!$res){
        $formCategory = Category::formAddCategory($_POST, "Le nom de la catégorie doit faire au moins 1 caractère");
    } else {
        $formCategory = Category::formAddCategory(array(), "La catégorie a été ajouté");
    }
} elseif (isset($_GET['a']) && isset($_GET['id'])) {
    $category = Category::getCategory($_GET['id']);
    $formCategory = $category->formEditCategory();
} else {
    $formCategory = Category::formAddCategory();
}

$wp = new WebPage('Administration', false);

$wp->appendCssUrl('../style/default/style.css');
$wp->appendContent($formCategory);
$wp->appendContent(Category::displayCategorys());

echo $wp->toHTML();
