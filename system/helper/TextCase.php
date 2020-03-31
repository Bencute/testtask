<?php


namespace system\helper;


/**
 * Class TextCase
 * @package system\helper
 */
class TextCase
{
    /**
     * @param string $str
     * @return string
     */
    public static function toPascalCase(string $str): string
    {
        return preg_replace_callback('/-([a-z0-9_])/i', function ($matches) {
            return ucfirst($matches[1]);
        }, ucfirst($str));
    }

    /**
     * @param string $str
     * @return string
     */
    public static function toCamelCase(string $str): string
    {
        return preg_replace_callback('/-|_([a-z0-9_])/i', function ($matches) {
            return ucfirst($matches[1]);
        }, lcfirst($str));
    }

    /**
     * @param string $str
     * @return string
     */
    public static function toSnakeCase(string $str): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $str));
    }
}