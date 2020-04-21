<?php

/**
 * Class UrlHelper
 */
class UrlHelper
{
    private static $stopWords = ['index.php', 'sitemap', 'login', 'cart', 'compare-products'];

    /**
     * @param $url
     *
     * @return boolean
     */
    public static function checkUrl($url): bool
    {
        $url = explode('/', $url);
        foreach (self::$stopWords as $stopWord) {
            if (in_array($stopWord, $url)) {
                return false;
            }
        }

        return true;
    }
}