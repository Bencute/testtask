<?php


namespace system\db;

use PDO;
use Sys;

// TODO сделать интерфейс для последующей реализиции любого наследуемого или нет класса

/**
 * Class Connect
 * @package system\db
 */
class Connect extends PDO
{
    /**
     * Connect constructor.
     * @param $dsn
     * @param null $username
     * @param null $passwd
     * @param null $options
     */
    public function __construct($dsn, $username = null, $passwd = null, $options = null)
    {
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setTimeZone();
    }

    /**
     * @param string|null $timeZone
     * @return bool
     */
    public function setTimeZone(?string $timeZone = null): bool
    {
        if (is_null($timeZone))
            $timeZone = Sys::getApp()->dbConnectTimeZone;

        return $this->exec("SET time_zone = '$timeZone'") !== false;
    }
}