<?php


namespace frontend\application;


use Exception;
use Sys;
use frontend\model\User;
use system\application\LoginInterface;
use system\application\WebUser;

/**
 * {@inheritdoc}
 * Class Web Application for frontend
 *
 * @package frontend\application
 */
class Application extends \system\application\Application
{
    /**
     * Время автологина
     * 86400 = 1 day
     */
    const DEFAULT_LOGIN_LIFETIME = 86400;

    /**
     * Имя параметра для смены языка через _GET массив
     */
    const GET_PARAM_LANGUAGE = 'lang';

    /**
     * Текущий язык приложения
     *
     * @var string
     */
    public string $lang = 'ru-RU';

    /**
     * язык приложения по умолчанию
     *
     * @var string
     */
    public string $defaultLang = 'ru-RU';

    /**
     * Параметры подключения к БД
     *
     * @var string
     */
    public string $dbDsn = '';

    /**
     * Пользователь БД
     *
     * @var string
     */
    public string $dbUser = '';

    /**
     * Пароль пользователя БД
     *
     * @var string
     */
    public string $dbPassword = '';

    /**
     * Временная зона соединения к БД
     *
     * @var string
     */
    public string $dbConnectTimeZone = 'Europe/Moscow';

    /**
     * @var array
     */
    public array $flashes = [];

    /**
     * @var WebUser|null
     */
    public ?WebUser $webUser = null;

    /**
     * Путь к директории для хранения загружаемых данных из frontend
     *
     * @var string
     */
    public string $pathData = 'data';

    /**
     * Префикс пути для генерации ссылки на загружаемые данные
     *
     * @var string
     */
    public string $urlPathRoot = '';

    /**
     * @throws Exception
     */
    public function init(): void
    {
        $this->initLang();
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function getControllerNamespace(): string
    {
        return 'frontend\controller';
    }

    /**
     * @param User $user
     * @throws Exception
     * @return bool
     */
    public function addUser(User $user): bool
    {
        return $user->save();
    }

    /**
     * @param string $email
     * @throws Exception
     * @return User|null
     */
    public function getUser(string $email): ?User
    {
        return User::find(['email' => $email]);
    }

    /**
     * @param string $id
     * @throws Exception
     * @return User|null
     */
    public function getUserById(string $id): ?User
    {
        return User::find(['id' => $id]);
    }

    /**
     * Авторизовывает пользователя
     *
     * @param LoginInterface $user
     * @param int|null $loginLifetime
     * @throws Exception
     * @return bool
     */
    public function login(LoginInterface $user, ?int $loginLifetime = self::DEFAULT_LOGIN_LIFETIME): bool
    {
        if (is_null($loginLifetime))
            $loginLifetime = self::DEFAULT_LOGIN_LIFETIME;

        $id = $user->getId();
        $webUser = $this->getWebUser();
        return $webUser->login($id, $loginLifetime);
    }

    /**
     * Снимает авторизацию пользователя
     *
     * @throws Exception
     * @return bool
     */
    public function logout(): bool
    {
        return $this->getWebUser()->logout();
    }

    /**
     * @param string $message
     * @param string $key
     * @param string $group
     */
    public function addFlash(string $message, string $key, string $group = 'general'): void
    {
        $this->flashes[$group][$key] = $message;
    }

    /**
     * @param string $key
     * @param string $group
     * @return string
     */
    public function getFlash(string $key, string $group = 'general'): string
    {
        return $this->flashes[$group][$key] ?? '';
    }

    /**
     * @param string $key
     * @param string $group
     * @return bool
     */
    public function existFlash(string $key, string $group = 'general'): bool
    {
        return isset($this->flashes[$group][$key]);
    }

    /**
     * @param string $group
     * @return array
     */
    public function getFlashesGroup(string $group = 'general'): array
    {
        return $this->flashes[$group] ?? [];
    }

    /**
     * @throws Exception
     * @return WebUser
     */
    public function getWebUser(): WebUser
    {
        if (is_null($this->webUser))
            $this->webUser = new WebUser();

        return $this->webUser;
    }

    /**
     * Возвращает путь до папки пользователя с загруженными данными
     *
     * @param string $getId
     * @param bool $create
     * @throws Exception
     * @return string
     */
    public function getPathDataUserId(string $getId, bool $create = false): string
    {
        return $this->getPath($this->getPathData($create) . '/' . $getId, $create);
    }

    /**
     * Возвращает путь до папки с загруженными данными
     *
     * @param bool $create
     * @throws Exception
     * @return string
     */
    public function getPathData(bool $create = false): string
    {
        return $this->getPath(Sys::getBaseDir() . '/web/' . $this->pathData, $create);
    }

    /**
     * Возвращает URL до папки пользователя с загруженными данными
     *
     * @param string $getId
     * @return string
     */
    public function getUrlPathDataUserId(string $getId): string
    {
        return $this->getUrlPathData() . '/' . $getId;
    }

    /**
     * Возвращает URL до папки с загруженными данными
     *
     * @return string
     */
    public function getUrlPathData(): string
    {
        return $this->urlPathRoot . '/' . $this->pathData;
    }

    /**
     * Список стран
     *
     * @return array
     */
    public function getCountries(): array
    {
        return [
            Sys::mId('app', 'countryRussia'),
            Sys::mId('app', 'countryUSA'),
            Sys::mId('app', 'countryItaly'),
            Sys::mId('app', 'countryFrance'),
            Sys::mId('app', 'countryGermany'),
            Sys::mId('app', 'countryUnitedKingdom'),
            Sys::mId('app', 'countryNorway'),
            Sys::mId('app', 'countryChina'),
            Sys::mId('app', 'countryJapan'),
            Sys::mId('app', 'countryIndia'),
            Sys::mId('app', 'countryUAE'),
        ];
    }

    /**
     * Инициализирует язык приложения
     *
     * @throws Exception
     */
    private function initLang(): void
    {
        if (isset($_GET[self::GET_PARAM_LANGUAGE])) {
            $lang = $_GET[self::GET_PARAM_LANGUAGE];
            if (parent::setLanguage($lang)) {
                $this->getWebUser()->setLang($lang);
            }
        } else {
            $lang = $this->getWebUser()->getLang();
            if (!is_null($lang)) {
                parent::setLanguage($lang);
            }
        }
    }
}