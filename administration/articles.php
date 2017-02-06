<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if($user->redacArticle == 0){
    header("Location: index.php");
}

$wp = new WebPage('Articles', false);

$wp->appendCssUrl('../style/default/style.css');

if(isset($_GET['a'])){
    if($_GET['a'] == 'a'){
        if(isset($_POST['formRedacArticle'])){
            if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category'])){
                $res = Article::addArticle($_POST);
                if(!$res){
                    $formRedacArticle = Article::formRedacArticle($_POST, "Erreur lors de l'ajout de l'article");
                } else {
                    $formRedacArticle = Article::formRedacArticle($_POST, "L'article a été ajouté");
                }
            } else {
                $formRedacArticle = Article::formRedacArticle($_POST, "Erreur lors de l'ajout de l'article");
            }
        } else {
            $formRedacArticle = Article::formRedacArticle();
        }
        $wp->appendContent($formRedacArticle);
    } elseif($_GET['a'] =='e') {
        if(isset($_POST['formEditArticle'])){
            if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category']) && isset($_POST['id'])){
                $res = Article::editArticle($_POST);
                if(!$res){
                    $formEditArticle = Article::formEditArticle($_POST, "Erreur lors de la modification de l'article");
                } else {
                    $formEditArticle = Article::formEditArticle($_POST, "L'article a été modifié");
                }
            } else {
                $formEditArticle = Article::formEditArticle($_POST, "Erreur lors de la modification de l'article");
            }
        } else {
            $formEditArticle = Article::formEditArticle();
        }
        $wp->appendContent($formEditArticle);
    } elseif($_GET['a'] =='d') {
        if(isset($_POST['formDeleteArticle'])){
            if(isset($_POST['id'])){
                $res = Article::deleteArticle($_POST['id']);
                if(!$res){
                    $formDeleteArticle = Article::formDeleteArticle("Erreur lors de la suppression de l'article");
                } else {
                    $formDeleteArticle = Article::displayArticles("L'article a été supprimé");
                }
            } else {
                $formDeleteArticle = Article::formDeleteArticle("Erreur lors de la suppression de l'article");
            }
        } else {
            $formDeleteArticle = Article::displayArticles();
        }
        $wp->appendContent($formDeleteArticle);
    }
} else {
    $wp->appendContent(Article::displayArticles());
}


echo $wp->toHTML();
