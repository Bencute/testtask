<?php


namespace frontend\helper;


use Sys;
use system\helper\FormHtml;
use frontend\application\Application;

/**
 * Class FormHtmlLanguage
 * @package frontend\helper
 */
class FormHtmlLanguage extends FormHtml
{
    /**
     * @param string $lang
     * @return string
     */
    public static function getCodeMessageNameLanguage(string $lang): string
    {
        return 'nameLanguage-' . $lang;
    }

    /**
     * @param string $lang
     * @return string
     */
    public static function getNameLanguage(string $lang): string
    {
        return Sys::mId('app', self::getCodeMessageNameLanguage($lang));
    }

    /**
     * @return string
     */
    public static function getCurrentNameLanguage(): string
    {
        return self::getNameLanguage(self::getCurrentLanguage());
    }

    /**
     * @return array
     */
    public static function getListSupportLanguages(): array
    {
        return Sys::getApp()->getSupportLanguages();
    }

    /**
     * @param $lang
     * @return string
     */
    public static function getLink($lang): string
    {
        $request = $_SERVER['REQUEST_URI'];
        $route = explode('?', $request)[0];

        $params = $_GET;
        unset($params[Application::GET_PARAM_LANGUAGE]);

        $linkParams = implode(
            '&',
            array_merge(
            /* array [name=>value] -> string "name=value" */
                array_map(
                    fn($name, $value) => $name . '=' . $value,
                    array_keys($params),
                    $params
                ),
                [Application::GET_PARAM_LANGUAGE . '=' . $lang]
            )
        );

        return $route . '?' . $linkParams;
    }

    /**
     * @return string
     */
    public static function getCurrentLanguage(): string
    {
        return Sys::getApp()->getLanguage();
    }
}