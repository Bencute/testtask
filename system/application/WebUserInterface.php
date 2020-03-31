<?php


namespace system\application;


/**
 * Interface WebUserInterface
 * @package frontend\model
 */
interface WebUserInterface
{
    /**
     * @param string $id
     * @param int $lifetime
     * @return bool
     */
    public function login(string $id, int $lifetime): bool;

    /**
     * @return bool
     */
    public function logout(): bool;

    /**
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface;

    /**
     * @return bool
     */
    public function isGuest(): bool;

    /**
     * @return string
     */
    public function getId(): ?string;

    /**
     * @param string $id
     * @return bool
     */
    public function setId(string $id): bool;
}