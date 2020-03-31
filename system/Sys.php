<?php


use system\application\Application;

/**
 * Class Sys
 * Статичный класс, позволяет получить доступ в базовой директории, сообщениям, экземпляру приложения
 */
class Sys
{
    /**
     * @var Application
     */
    public static Application $app;

    /**
     * @var string
     */
    public static string $baseDir = __DIR__;

    /**
     * @return Application
     */
    public static function getApp(): Application
    {
        return self::$app;
    }

    /**
     * @param Application $app
     */
    public static function setApp(Application $app): void
    {
        self::$app = $app;
    }

    /**
     * @param string $file
     * @param $id
     * @param array $params
     * @return string
     */
    public static function mId(string $file, $id, array $params = []): string
    {
        $messages = self::getMessagesByLang($file);
        $message = $messages[$id] ?? $id;
        return MessageFormatter::formatMessage(self::getApp()->getLanguage(), $message, $params);
    }

    /**
     * @param $file
     * @return array
     */
    private static function getMessagesByLang($file): array
    {
        $lang = self::getApp()->getLanguage();
        $file = self::getBaseDir() . '/message/' . $lang . '/' . $file . '.php';
        if (!file_exists($file)) {
            $defaultLang = self::getApp()->getDefaultLanguage();
            $file = self::getBaseDir() . '/message/' . $defaultLang . '/' . $file . '.php';
        }

        return require $file;
    }

    /**
     * @param string $dir
     */
    public static function setBaseDir(string $dir): void
    {
        self::$baseDir = $dir;
    }

    /**
     * @return string
     */
    public static function getBaseDir(): string
    {
        return self::$baseDir;
    }
}