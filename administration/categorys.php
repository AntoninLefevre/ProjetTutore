<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if($user->isAdministrator == 0){
    header("Location: index.php");
}

$wp = new WebPage('Categories', false);

$wp->appendCssUrl('../style/default/style.css');

if(isset($_GET['a'])){
    if($_GET['a'] == 'a'){
        if(isset($_POST['formAddCategory'])){
            if(isset($_POST['lblCategory']) && isset($_POST['category'])){
                $res = Category::addCategory($_POST);
                if(!$res){
                    $formAddCategory = Category::formAddCategory($_POST, "Le nom de la catégorie doit faire au moins 1 caractère");
                } else {
                    $formAddCategory = Category::formAddCategory(array(), "La catégorie a été ajouté");
                }
            } else {
                    $formAddCategory = Category::formAddCategory($_POST, "Erreur lors de l'ajout de la catégorie");
            }
        } else {
            $formAddCategory = Category::formAddCategory();
        }
        $wp->appendContent($formAddCategory);
    } elseif($_GET['a'] == 'e') {
        if(isset($_GET['id'])){
            $category = Category::getCategory($_GET['id']);
            if(!$category){
                header('Location: categorys.php');
            } else {
                if(isset($_POST['formEditCategory'])){
                    if(isset($_POST['lblCategory']) && isset($_POST['category'])){
                        $res = $category->editCategory($_POST);
                        if(!$res){
                            $formEditCategory = $category->formEditCategory($_POST, "Le nom de la catégorie doit faire au moins 1 caractère");
                        } else {
                            $formEditCategory = $category->formEditCategory($_POST, "La catégorie a été modifiée");
                        }
                    }else {
                        $formEditCategory = $category->formEditCategory($_POST, "Erreur lors de la modification de la categorie");
                    }
                } else {
                    $formEditCategory = $category->formEditCategory();
                }
                $wp->appendContent($formEditCategory);
            }
        } else {
            header('Location: categorys.php');
        }
    } elseif($_GET['a'] == 'd'){
        if(isset($_GET['id'])){
            $category = Category::getCategory($_GET['id']);
            if(!$category){
                header('Location: categorys.php');
            } else {
                if(isset($_POST['formDeleteCategory'])){
                    $category->deleteCategory();
                    $formDeleteCategory = Category::displayCategorys("La catégorie a été supprimée");
                } elseif(isset($_POST['cancelDeleteCategory'])){
                    header('Location: categorys.php');
                } else {
                    $formDeleteCategory = $category->formDeleteCategory();
                }
                $wp->appendContent($formDeleteCategory);
            }
        } else {
            header('Location: categorys.php');
        }
    } else {
        header('Location: categorys.php');
    }
} else {
    $wp->appendContent(Category::displayCategorys());
}

echo $wp->toHTML();
