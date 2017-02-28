<?php

include_once('autoload.inc.php');

$category = Category::getCategory($_GET['id']);
$breadcrumb = $category->getBreadcrumb();
$wp = new Webpage($siteOptions['siteName'] . " - " . $category->lblCategory);

$listChildren = $category->getIdChildren();

$list[] = $_GET['id'];

if(!is_null($listChildren)){
    foreach ($listChildren as $value) {
            $list[] = $value;
    }
}

$nbArticles = Article::countArticlesPerCategory($list);
$articlesPerPage = $siteOptions['articlesPerPage'] <= 0 ? $nbArticles : $siteOptions['articlesPerPage'];

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

$articles = Article::getArticlesPerCategory($list, ["limit" => $articlesPerPage, "offset" => ($p - 1) * $articlesPerPage]);

if($articles)
    $wp->appendContent(Article::displayArticlesLimit($articles));
else
    $wp->appendContent("<div class='row text-center'>Aucun article dans la catégorie " . $category->lblCategory . "</div>");

$wp->appendContent($pagination);

$wp->appendContent("<div class='row'><div class='col-md-10 col-md-offset-1' ><ol class='breadcrumb'>" . $breadcrumb . "</ol></div></div>");

echo $wp->toHTML();
