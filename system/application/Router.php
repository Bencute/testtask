<?php


namespace system\application;


use Exception;
use Sys;
use system\helper\TextCase;

/**
 * Class Router
 * @package system\application
 */
class Router
{
    /**
     * Идентификатор контроллера
     * @var string
     */
    public string $idController;

    /**
     * Идентификатор действия контроллера
     * @var string|null
     */
    public ?string $idAction = null;

    /**
     * Router constructor.
     * @param string|null $route
     * @throws Exception
     */
    public function __construct(?string $route = null)
    {
        if (is_null($route)) {
            $uri = $_SERVER['REQUEST_URI'];
            $route = explode('?', $uri)[0];
        }

        $route = trim($route, '/\\');

        $parseRoute = [];

        if (!empty($route))
            $parseRoute = explode('/', $route);

        switch (count($parseRoute)) {
            case 0:
                $this->idController = Sys::getApp()->getDefaultController();
                break;
            case 1:
                $this->idAction = $parseRoute[0];
                $this->idController = Sys::getApp()->getDefaultController();
                break;
            case 2:
                $this->idController = $parseRoute[0];
                $this->idAction = $parseRoute[1];
                break;
            default:
                throw new Exception(Sys::mId('error', 'exceptionInvalidRoute', ['route' => $route]));
        }
    }

    /**
     * @param string $param
     * @return string
     */
    public static function normalizeRouteParams(string $param): string
    {
        return TextCase::toPascalCase($param);
    }

    /**
     * @return string
     */
    public function getFullClassnameController(): string
    {
        $classNameController = self::normalizeRouteParams($this->idController . 'Controller');
        return Sys::getApp()->getControllerNamespace() . '\\' . $classNameController;
    }

    /**
     * @return string|null
     */
    public function getFullActionMethodController(): ?string
    {
        if (is_null($this->idAction))
            return null;

        return self::normalizeRouteParams($this->idAction);
    }
}