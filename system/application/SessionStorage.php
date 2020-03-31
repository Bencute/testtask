<?php


namespace system\application;


use Exception;
use Sys;

/**
 * Class SessionStorage
 * @package frontend\model
 */
class SessionStorage implements StorageInterface
{
    /**
     * Ключ в массиве SESSION
     */
    const NAME_PARAM_DELETE_TIME = 'deleteTime';

    /**
     * Время жизни сессии на основе параметра NAME_PARAM_DELETE_TIME
     * В секундах
     *
     * @var int
     */
    protected int $sessionTimeLeft = 86400;

    /**
     * @var array
     */
    protected array $sessionStartParams = [
        'cookie_lifetime' => 86400, // 1 day
        'cookie_samesite' => 'Strict',
        'cookie_httponly' => true,
    ];

    /**
     * SessionStorage constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config = [])
    {
        $this->sessionStartParams = array_merge($this->sessionStartParams, $config);
        @ini_set('session.use_strict_mode', 1);

        if (!$this->sessionStart())
            throw new Exception(Sys::mId('error', 'exceptionSessionDontStart'));
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function add(string $key, $value): bool
    {
        $_SESSION[$key] = $value;
        return true;
    }

    /**
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (isset($_SESSION[$key]))
            unset($_SESSION[$key]);
        return true;
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        $_SESSION = [];
        return true;
    }

    /**
     * @return bool
     */
    private function sessionStart(): bool
    {
        $this->sessionStop();
        $result = session_start($this->sessionStartParams);
        if ($result && !empty($_SESSION[self::NAME_PARAM_DELETE_TIME]) && $_SESSION[self::NAME_PARAM_DELETE_TIME] < time() - $this->sessionTimeLeft)
            return $this->sessionRegenerateId();

        return $result;
    }

    /**
     * @return bool
     */
    public function sessionRegenerateId(): bool
    {
        if (session_status() != PHP_SESSION_ACTIVE && !session_start($this->sessionStartParams))
            return false;

        $_SESSION[self::NAME_PARAM_DELETE_TIME] = time();
        return session_regenerate_id();
    }

    /**
     * @return bool
     */
    private function sessionStop(): bool
    {
        if (session_status() == PHP_SESSION_ACTIVE)
            return session_write_close();

        return true;
    }

    /**
     * @param int $lifetime
     * @return bool
     */
    public function setLifetime(int $lifetime): bool
    {
        if (!$this->sessionStop())
            return false;

        $this->sessionStartParams['cookie_lifetime'] = $lifetime;
        return $this->sessionStart();
    }
}