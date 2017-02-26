<?php

include_once('autoload.inc.php');

$user = User::createFromSession();


if(isset($_GET['idC'])){
    $category = Category::getCategory($_GET['idC']);
    if($category){
        $wp = New WebPage($category->lblCategory);
        $wp->appendContent(Category::displayForum($_GET['idC']));
    } else {
        header("Location: forum.php");
    }
} elseif(isset($_GET['idA'])){
    $article = Article::getArticle($_GET['idA']);
    if($article){
        $info = "";
        if(isset($_POST['formAddComment'])){
            if(!User::isConnected()){
                header("Location: connexion.php");
            }
            Comment::addComment($_POST, $_GET['idA']);
            $info = "Votre commentaire a été ajouté";
        }
        $wp = New WebPage($article->titleArticle);
        $wp->appendContent($article->displayComment($_GET['idA']));
        if(User::isConnected()){
            $wp->appendContent(Comment::formAddComment(array(), $info));
        } else {
            $wp->appendContent(<<<HTML
                <a href="connexion.php">Connectez-vous</a> pour commenter
HTML
            );
        }
    } else {
        header("Location: forum.php");
    }
} else {
    $wp = New WebPage("Forum");
    $wp->appendContent(Category::displayForum());
}

echo $wp->toHTML();
