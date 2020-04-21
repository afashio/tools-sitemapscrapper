<?php
require_once 'XMLMapper.php';
require_once 'SiteMapCreator.php';

$root = simplexml_load_string(file_get_contents('./sitemap.xml'));

$mapper = new XMLMapper($root);
$mapper->parse();
$data = $mapper->getResult();

$results = [];

if ($data){
    foreach ($data as $key => $pages){
        $siteMap = new SiteMapCreator($pages, $key);
        $results[] = $siteMap->createSitemap();
    }
}

foreach ($results as $result) {
    echo $result . PHP_EOL;
}