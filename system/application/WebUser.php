<?php


namespace system\application;


use Exception;
use Sys;
use frontend\model\User;

/**
 * Class WebUser
 * Класс представляет собой сущность пользователя который взаимодействует с приложением
 *
 * @package frontend\model
 */
class WebUser implements WebUserInterface
{
    /**
     * Время хранения хранилища данных в секундах до уничтожения по умолчанию
     */
    const DEFAULT_LIFETIME_STORAGE = 86400;

    /**
     * Ключ в хранилище для хранения быстрых сообщений
     */
    const STORAGE_FLASHES_KEY = 'flash';

    /**
     * Ключ в хранилище для хранения языка пользователя
     */
    const STORAGE_LANG_KEY = 'lang';

    /**
     * Время хранения авторизации в секундах по умолчанию
     * Если 0 то до закрытия браузера
     */
    const DEFAULT_LIFETIME_LOGIN = self::DEFAULT_LIFETIME_STORAGE;

    /**
     * Ключ в хранилище для хранения токена csrf
     */
    const STORAGE_CSRF_KEY = 'csrf';

    /**
     * Ключ в _POST массиве для токена csrf
     */
    const POST_CSRF_KEY = 'csrf_token';

    /**
     * Идентификатор в хоранилище имени параметра для идентификации пользователя
     *
     * lgi -> LoGin Id
     */
    const LOGIN_PARAM = 'lgi';

    /**
     * @var StorageInterface|null
     */
    protected ?StorageInterface $storage = null;

    /**
     * Идентификатор авторизованного пользователя.
     * Не авторизованного хранится null
     *
     * @var string|null
     */
    protected ?string $id = null;

    /**
     * Время хранения хранилища данных до уничтожения по умолчанию
     * В секундах
     *
     * @var int
     */
    protected int $lifetimeStorage;

    /**
     * Текущий csrf токен
     *
     * @var string|null
     */
    private ?string $csrfToken = null;

    /**
     * Показывает валидный был передан токен или нет
     * Инициализируется в конструкторе
     *
     * @var bool
     */
    private bool $stateValidCsrf;

    /**
     * WebUser constructor.
     *
     * @param int $lifetimeStorage
     * @throws Exception
     */
    public function __construct(int $lifetimeStorage = self::DEFAULT_LIFETIME_STORAGE)
    {
        $this->lifetimeStorage = $lifetimeStorage;
        $this->stateValidCsrf = $this->checkCsrf();
        $this->getCsrfToken(true);
    }

    /**
     * $id идентификатор пользователя
     * $lifetime время хранения авторизации в секундах
     *
     * @param string $id
     * @param int $lifetime
     * @throws Exception
     * @return bool
     */
    public function login(string $id, int $lifetime = self::DEFAULT_LIFETIME_LOGIN): bool
    {
        $this->setId($id);
        $storage = $this->getStorage();
        if (!$this->getStorage()->setLifetime($lifetime) || !$storage->add(self::LOGIN_PARAM, $this->getId()))
            return false;

        return $this->getStorage()->sessionRegenerateId();
    }

    /**
     * Снятие авторизации
     *
     * @throws Exception
     * @return bool
     */
    public function logout(): bool
    {
        if ($this->isGuest())
            return true;

        if (!$this->getStorage()->delete(self::LOGIN_PARAM))
            return false;

        return $this->getStorage()->sessionRegenerateId();
    }

    /**
     * @throws Exception
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        if (is_null($this->storage))
            $this->storage = new SessionStorage(['cookie_lifetime' => $this->lifetimeStorage]);

        return $this->storage;
    }

    /**
     * Возвращает авторизован ли пользователь
     *
     * @throws Exception
     * @return bool
     */
    public function isGuest(): bool
    {
        return !$this->getStorage()->isset(self::LOGIN_PARAM) || empty($this->getStorage()->get(self::LOGIN_PARAM));
    }

    /**
     * Получает модель пользователя
     *
     * @throws Exception
     * @return User|null
     */
    public function getUser(): ?LoginInterface
    {
        if ($this->isGuest())
            return null;

        return Sys::getApp()->getUserById($this->getId());
    }

    /**
     * @param string $id
     * @return bool
     */
    public function setId(string $id): bool
    {
        $this->id = $id;
        return true;
    }

    /**
     * @throws Exception
     * @return string
     */
    public function getId(): ?string
    {
        if (is_null($this->id))
            $this->id = $this->getStorage()->get(self::LOGIN_PARAM);

        return $this->id;
    }

    /**
     * Добавляет быстрое сообщение по ключу по группе
     *
     * @param string $message
     * @param string $key
     * @param string $group
     * @throws Exception
     */
    public function addFlash(string $message, string $key, string $group = 'general'): void
    {
        $flashes = $this->getStorage()->get(self::STORAGE_FLASHES_KEY);
        $flashes[$group][$key] = $message;
        $this->getStorage()->add(self::STORAGE_FLASHES_KEY, $flashes);
    }

    /**
     * Получает быстрое сообщение по ключу и группе, затем удаляет его
     *
     * @param $group
     * @param bool $delete
     * @throws Exception
     * @return array
     */
    public function getFlashesGroup($group, bool $delete = true): array
    {
        $flashes = $this->getStorage()->get(self::STORAGE_FLASHES_KEY);
        $flashesGroup = $flashes[$group] ?? [];
        if ($delete && isset($flashes[$group])) {
            unset($flashes[$group]);
            $this->getStorage()->add(self::STORAGE_FLASHES_KEY, $flashes);
        }

        return $flashesGroup;
    }

    /**
     * Получает текущий csrf токен
     * Если токен еще не генерировался, то генерирует новый
     *
     * @param bool $regenerate
     * @throws Exception
     * @return string
     */
    public function getCsrfToken(bool $regenerate = false): string
    {
        $this->csrfToken = $this->getStorage()->get(self::STORAGE_CSRF_KEY);
        if ($regenerate || is_null($this->csrfToken)) {
            $this->csrfToken = $this->generateCsrfToken();
            $this->getStorage()->add(self::STORAGE_CSRF_KEY, $this->csrfToken);
        }
        return $this->csrfToken;
    }

    /**
     * @throws Exception
     * @return string
     */
    private function generateCsrfToken(): string
    {
        return hash('sha3-512', $this->getId() . microtime());
    }

    /**
     * Сверяет переданный csrf токен с хранимым от предыдущей генерации
     *
     * @throws Exception
     * @return bool
     */
    private function checkCsrf(): bool
    {
        if (!isset($_POST[$this->getCsrfKey()]))
            return false;

        return $_POST[$this->getCsrfKey()] === $this->getCsrfToken();
    }

    /**
     * Получает ключ csrf токена в _POST массиве
     *
     * @return string
     */
    public function getCsrfKey(): string
    {
        return self::POST_CSRF_KEY;
    }

    /**
     * Возвращает валидный ли был передан токен в запросе
     *
     * @return bool
     */
    public function isValidCsrf(): bool
    {
        return $this->stateValidCsrf;
    }

    /**
     * Записывает язык пользователя в хранилище
     *
     * @param string $lang
     * @throws Exception
     * @return bool
     */
    public function setLang(string $lang): bool
    {
        return $this->getStorage()->add(self::STORAGE_LANG_KEY, $lang);
    }

    /**
     * Возвращает язык пользователя, или null если еще не устанавливался
     *
     * @throws Exception
     * @return string|null
     */
    public function getLang(): ?string
    {
        return $this->getStorage()->get(self::STORAGE_LANG_KEY);
    }
}