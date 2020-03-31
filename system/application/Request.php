<?php


namespace system\application;


/**
 * Class Request
 * @package system\application
 */
class Request
{
    /**
     * HTTP method POST
     */
    const REQUEST_METHOD_POST = 'POST';

    /**
     * @return bool
     */
    public function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === self::REQUEST_METHOD_POST;
    }
}