<?php

include_once('autoload.inc.php');

$user = User::createFromSession();


if(isset($_GET['idC'])){
    $category = Category::getCategory($_GET['idC']);
    if($category){
        $wp = New WebPage($siteOptions['siteName'] . " - " . $category->lblCategory);
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
            $res = Comment::addComment($_POST, $_GET['idA']);
            if($res)
                $info = "Le commentaire a été ajouté";
            else
                $info = "Le captcha est incorrect";
        }
        $wp = New WebPage($siteOptions['siteName'] . " - " . $article->titleArticle);
        if(User::isConnected()){
            $wp->appendContent(Comment::formAddComment(array(), $info));
        } else {
            $wp->appendContent(<<<HTML
                <div class="row text-center"><a href="connexion.php">Connectez-vous</a> pour commenter</div>
HTML
            );
        }

        $nbComments = Comment::countComments($_GET['idA']);
        $commentsPerPage = $siteOptions['commentsPerPage'] <= 0 ? $nbComments : $siteOptions['commentsPerPage'];
        $nbPages = intval(ceil($nbComments / $commentsPerPage));

        $page = isset($_GET['p']) ? intval($_GET['p']) : "1";
        if($page <= 1){
            $p = 1;
        } elseif($_GET['p'] >= $nbPages){
            $p = $nbPages;
        } else {
            $p = $_GET['p'];
        }

        $comments = Comment::getCommentsPerArticle($_GET['idA'], ["limit" => $commentsPerPage, "offset" => ($p - 1) * $commentsPerPage]);

        $wp->appendContent($article->displayComment($comments));

        $pagination = Comment::displayPagination($p, $nbPages, $_GET['idA']);
        $wp->appendContent($pagination);
    } else {
        header("Location: forum.php");
    }
} else {
    $wp = New WebPage($siteOptions['siteName'] . " - " . "Forum");
    $wp->appendContent(Category::displayForum());
}

echo $wp->toHTML();
