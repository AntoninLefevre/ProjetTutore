<?php

include_once('autoload.inc.php');

if(isset($_GET['id'])){
    $article = Article::getArticle($_GET['id']);
    if($article){
        $wp = new Webpage($siteOptions['siteName'] . " - " . $article->titleArticle);
        $wp->appendContent($article->displayArticle(false));
        $categoryArticle = Category::getCategory($article->idCategory);
        $breadcrumb = $categoryArticle->getBreadcrumb();
        $wp->appendContent("<div class='row'><div class='col-md-10 col-md-offset-1'><ol class='breadcrumb'>" . $breadcrumb . "<li><a href='articles.php?id=" . $article->idArticle . "'>" . $article->titleArticle . "</a></li></ol></div></div>");
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

echo $wp->toHTML();
