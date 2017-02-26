<?php

include_once('autoload.inc.php');

$wp = new Webpage("Article", false);

$wp->appendCssUrl('../style/default/style.css');


if(isset($_GET['idA'])){
    $article = Article::getArticle($_GET['idA']);
    if($article){
        if(isset($_GET['idC'])){
            $comment = Comment::getComment($_GET['idC']);
            if($comment){
                if($comment->idArticle == $_GET['idA'] && isset($_GET['a'])){
                    if($_GET['a'] == 'e') {
                        if(isset($_POST['formEditComment'])){
                            if(isset($_POST['content'])){
                                $comment->editComment($_POST);
                                $comment = Comment::getComment($_GET['idC']);
                                $formEditComment = $comment->formEditComment(array(), "Le commentaire a été modifié");
                            } else {
                                $formEditComment = $comment->formEditComment($_POST, "Erreur lors de la modification du commentaire");
                            }
                        } else {
                            $formEditComment = $comment->formEditComment();
                        }
                        $wp->appendContent($formEditComment);
                    } elseif($_GET['a'] == 'd') {
                        if(isset($_POST['formDeleteComment'])){
                            $comment->deleteComment();
                            header("Location: comments.php?idA=" . $_GET['idA']);
                        } elseif(isset($_POST['cancelDeleteComment'])){
                            header("Location: comments.php?idA=" . $_GET['idA']);
                        } else {
                            $formDeleteComment = $comment->formDeleteComment();
                            $wp->appendContent($formDeleteComment);
                        }
                    } else {
                        header("Location: comments.php?idA=" . $_GET['idA']);
                    }
                } else {
                    header("Location: comments.php?idA=" . $_GET['idA']);
                }
            } else {
                header("Location: comments.php?idA=" . $_GET['idA']);
            }
        } else {
            $comments = Comment::displayCommentsAdmin($article->idArticle);
            $wp->appendContent($comments);
        }
    } else {
        header("Location: articles.php");
    }
} else {
    header("Location: articles.php");
}

echo $wp->toHTML();
