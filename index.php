<?php

require_once('autoload.inc.php');

$wp = new WebPage("Accueil");

$nbArticles = Article::countArticles();
$articlesPerPage = $siteOptions['articlesPerPage'] <= 0 ? $nbArticles : $siteOptions['articlesPerPage'];

if($nbArticles > 0){
    $nbPages = intval(ceil($nbArticles / $articlesPerPage));

    $page = isset($_GET['p']) ? intval($_GET['p']) : "1";
    if($page <= 1){
        $p = 1;
    } elseif($_GET['p'] >= $nbPages){
        $p = $nbPages;
    } else {
        $p = $_GET['p'];
    }

    $pagination = Article::displayPagination($p, $nbPages);


    $articles = Article::getArticles(["limit" => $articlesPerPage, "offset" => ($p - 1) * $articlesPerPage]);

    $wp->appendContent(Article::displayArticlesLimit($articles));

    $wp->appendContent($pagination);
} else {
    $wp->appendContent("Page d'accueil");
}

echo $wp->toHTML();
