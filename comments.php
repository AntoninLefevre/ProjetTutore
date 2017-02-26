<?php

include_once('autoload.inc.php');

$wp = new Webpage("Article");

$wp->appendCssUrl('style/default/style.css');


if(isset($_GET['id'])){
    $article = Article::getArticle($_GET['id']);
    if($article){
        if(isset($_POST['formAddComment'])){
            if(isset($_POST['content'])){
                Comment::addComment($_POST, $_GET['id']);
                $formAddComment = Comment::formAddComment(array(), "Le commentaire a été ajouté");
            } else {
                $formAddComment = Comment::formAddComment(array(), "Erreur lors de l'ajout du commentaire");
            }
        } else {
            $formAddComment = Comment::formAddComment();
        }
        $comments = Comment::displayComments($article->idArticle);
    } else {
        $formAddComment = Comment::formAddComment();
    }
} else {
    $formAddComment = Comment::formAddComment();
}

$wp->appendContent($comments);
$wp->appendContent($formAddComment);

echo $wp->toHTML();
