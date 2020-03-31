<?php


namespace system\application;


use frontend\model\User;
use SplFileInfo;

/**
 * Class Image
 * @package system\application
 */
class Image extends SplFileInfo
{
    /**
     * @var User
     */
    public User $user;

    /**
     * Image constructor.
     * @param User $user
     * @param $file_name
     */
    public function __construct(User $user, $file_name)
    {
        parent::__construct($file_name);
        $this->user = $user;
    }

    /**
     * @return bool
     */
    public function remove(): bool
    {
        return unlink($this->getRealPath());
    }

    /**
     * @return string
     */
    public function getUrlPath(): string
    {
        return $this->user->getUrlPath() . '/' . $this->getFilename();
    }
}