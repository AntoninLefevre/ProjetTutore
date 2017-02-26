<?php

include_once('autoload.inc.php');

if(isset($_GET['id'])){
    $article = Article::getArticle($_GET['id']);
    if($article){
        $wp = new Webpage($siteOptions['siteName'] . " - " . $article->titleArticle);
        $wp->appendContent($article->displayArticle(false));
        $categoryArticle = Category::getCategory($article->idCategory);
        $breadcrumb = $categoryArticle->getBreadcrumb();
        $wp->appendContent("Vous êtes ici: " . $breadcrumb . " > " . "<a href='articles.php?id=" . $article->idArticle . "'>" . $article->titleArticle . "</a>");
    } else {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

$wp->appendCssUrl('style/'. $siteOptions['theme'] . '/style.css');

echo $wp->toHTML();
