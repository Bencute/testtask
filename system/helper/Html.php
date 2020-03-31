<?php


namespace system\helper;


/**
 * Class Html
 * @package system\helper
 */
class Html
{
    /**
     * Кодирует html сущности
     *
     * @param string $str
     * @return string
     */
    public static function encode(string $str): string
    {
        return htmlentities($str, ENT_QUOTES | ENT_HTML5);
    }
}