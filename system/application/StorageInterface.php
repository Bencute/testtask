<?php


namespace system\application;


/**
 * Interface StorageInterface
 * @package frontend\model
 */
interface StorageInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function add(string $key, $value): bool;

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $key
     * @return bool
     */
    public function isset(string $key): bool;

    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * @return bool
     */
    public function clear(): bool;
}