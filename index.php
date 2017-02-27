<?php

require_once('autoload.inc.php');

$wp = new WebPage("Accueil");

$p = 1;

$articles = Article::getArticles(["limit" => $siteOptions['articlesPerPage'], "offset" => ($p - 1) * $siteOptions['articlesPerPage']]);

if($articles)
    $wp->appendContent(Article::displayArticlesLimit($articles));
else
    $wp->appendContent("Page d'accueil");
echo $wp->toHTML();
