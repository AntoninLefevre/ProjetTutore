<?php

require_once('autoload.inc.php');

$user = User::createFromSession();

if(!$user->redacArticle && !$user->editOwnArticle && !$user->deleteOwnArticle && !$user->isAdministrator){
    header("Location: index.php");
}

$wp = new WebPage('Articles', false);

if(isset($_GET['a'])){
    if(!$user->redacArticle && !$user->isAdministrator)
        header("Location: articles.php");

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
        if(isset($_GET['id'])){
            $article = Article::getArticle($_GET['id']);
            if(!$article){
                header('Location: articles.php');
            } else {
                if((!$user->editOwnArticle || $user->idUser != $article->idUser) && !$user->isAdministrator)
                    header("Location: articles.php");

                if(isset($_POST['formEditArticle'])){
                    if(isset($_POST['title']) && isset($_POST['content']) && isset($_POST['category'])){
                        $res = $article->editArticle($_POST);
                        if(!$res){
                            $formEditArticle = $article->formEditArticle($_POST, "Erreur lors de la modification de l'article");
                        } else {
                            $formEditArticle = $article->formEditArticle($_POST, "L'article a été modifié");
                        }
                    } else {
                        $formEditArticle = $article->formEditArticle($_POST, "Erreur lors de la modification de l'article");
                    }
                } else {
                    $formEditArticle = $article->formEditArticle();
                }
                $wp->appendContent($formEditArticle);
            }
        } else {
            header('Location: articles.php');
        }
    } elseif($_GET['a'] == 'd') {
        if(isset($_GET['id'])){
            $article = Article::getArticle($_GET['id']);
            if(!$article){
                header('Location: articles.php');
            } else {
                if((!$user->deleteOwnArticle || $user->idUser != $article->idUser) && !$user->isAdministrator)
                    header("Location: articles.php");

                if(isset($_POST['formDeleteArticle'])){
                    $article->deleteArticle();
                    $formDeleteArticle = Article::displayArticles("L'article a été supprimé");
                } elseif(isset($_POST['cancelDeleteArticle'])){
                    header('Location: articles.php');
                } else {
                    $formDeleteArticle = $article->formDeleteArticle();
                }
                $wp->appendContent($formDeleteArticle);
            }
        } else {
            header('Location: articles.php');
        }
    } else {
        header('Location: articles.php');
    }
} else {
    $wp->appendContent(Article::displayArticles());
}


echo $wp->toHTML();
