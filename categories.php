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
            $list[] = $value;
    }
}

$p = 1;

$articles = Article::getArticlesPerCategory($list, ["limit" => $siteOptions['articlesPerPage'], "offset" => ($p - 1) * $siteOptions['articlesPerPage']]);

if($articles)
    $wp->appendContent(Article::displayArticlesLimit($articles));
else
    $wp->appendContent("<div class='row text-center'>Aucun article dans la catégorie " . $category->lblCategory . "</div>");

$wp->appendContent("<div class='row'><div class='col-md-offset-1' >Vous êtes ici: " . $breadcrumb . "</div></div>");

echo $wp->toHTML();
