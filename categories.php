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

$articles = Article::getArticlesPerCategory($list);

if($articles){
    foreach ($articles as $article) {
        $wp->appendContent($article->displayArticle());
    }
} else {
    $wp->appendContent("<div>Aucun article dans la catégorie " . $category->lblCategory);
}

$wp->appendContent("Vous êtes ici: " . $breadcrumb);

echo $wp->toHTML();
