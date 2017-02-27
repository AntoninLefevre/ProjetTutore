<?php

include_once('autoload.inc.php');

$category = Category::getCategory($_GET['id']);
$breadcrumb = $category->getBreadcrumb();
$wp = new Webpage($siteOptions['siteName'] . " - " . $category->lblCategory);

$wp->appendCssUrl('style/default/style.css');

$listChildren = $category->getIdChildren();

$list[] = $_GET['id'];

if(!is_null($listChildren)){
    foreach ($listChildren as $value) {
        if(is_array($value)){
            foreach ($value as $id) {
                $list[] = $id;
            }
        } else {
            $list[] = $value;
        }
    }
}

$p = 1;

$articles = Article::getArticlesPerCategory($list, ["limit" => $siteOptions['articlesPerPage'], "offset" => ($p - 1) * $siteOptions['articlesPerPage']]);

if($articles)
    $wp->appendContent(Article::displayArticlesLimit($articles));
else
    $wp->appendContent("Page d'accueil");

$wp->appendContent("<div class='row'>Vous êtes ici: " . $breadcrumb . "</div>");

echo $wp->toHTML();
