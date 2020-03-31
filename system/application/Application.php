<?php


namespace system\application;

use Exception;
use ReflectionProperty;
use Sys;
use system\controller\Controller;
use system\db\Connect;

/**
 * Base class Application
 * @package system
 */
abstract class Application
{
    /**
     * @var Controller
     */
    public Controller $controller;

    /**
     * @var string
     */
    public string $dbConnectTimeZone = 'UTC';

    /**
     * @var Router|null
     */
    public ?Router $router = null;

    /**
     * @var string
     */
    public string $classnameRouter = 'system\application\Router';

    /**
     * Id контроллера по умолчанию
     * @var string
     */
    public string $defaultController = 'default';

    /**
     * @var string
     */
    public string $lang = 'en-US';

    /**
     * @var string
     */
    public string $defaultLang = 'en-US';

    /**
     * @var string
     */
    public string $dbDsn;

    /**
     * @var string
     */
    public string $dbUser;

    /**
     * @var string
     */
    public string $dbPassword;

    /**
     * @var array
     */
    public array $dbOptions = [];

    /**
     * @var Connect|null
     */
    public ?Connect $db = null;

    /**
     * @var Request|null
     */
    public ?Request $request = null;

    /**
     * @var array
     */
    public array $log = [];

    /**
     * Application constructor.
     */
    public function __construct(array $params = [])
    {
        defined('DEBUG_MODE') or define('DEBUG_MODE', false);

        if (DEBUG_MODE) {
            ini_set('display_errors', '1');
            ini_set('display_startup_errors', '1');
            ini_set('log_errors', '1');
            error_reporting(E_ALL);
        } else {
            @ini_set('display_errors', '0');
            @ini_set('display_startup_errors', '0');
            @ini_set('log_errors', '1');
            @error_reporting(0);
        }

        $this->initAttributes($params);

        Sys::setApp($this);
    }

    /**
     * @param array $attributes
     * @throws \ReflectionException
     */
    private function initAttributes(array $attributes): void
    {
        foreach ($attributes as $nameAttribute => $valueAttribute) {
            if (property_exists($this, $nameAttribute)) {
                $prop = new ReflectionProperty($this, $nameAttribute);
                if ($prop->isPublic())
                    $this->$nameAttribute = $valueAttribute;
            }
        }
    }

    /**
     * Run the application
     * @throws Exception
     */
    public function init(): void
    {
        $this->run();
    }

    /**
     * @throws Exception
     */
    public function run(): void
    {
        $router = $this->getRouter();
        $classNameController = $router->getFullClassnameController();
        $action = $router->getFullActionMethodController();

        $this->controller = new $classNameController($this);
        $result = $this->controller->runAction($action);
        echo $result;
    }

    /**
     * @return string
     */
    abstract public function getControllerNamespace(): string;

    /**
     * @return Router
     */
    public function getRouter(): Router
    {
        if (is_null($this->router)) {
            $classnameRouter = $this->getClassnameRouter();
            $this->router = new $classnameRouter();
        }

        return $this->router;
    }

    /**
     * @param Router $router
     */
    public function setRouter(Router $router): void
    {
        $this->router = $router;
    }

    /**
     * @return string
     */
    public function getClassnameRouter(): string
    {
        return $this->classnameRouter;
    }

    /**
     * @param string $classnameRouter
     */
    public function setClassnameRouter(string $classnameRouter): void
    {
        $this->classnameRouter = $classnameRouter;
    }

    /**
     * @return string
     */
    public function getDefaultController(): string
    {
        return $this->defaultController;
    }

    /**
     * @param string $defaultController
     */
    public function setDefaultController(string $defaultController): void
    {
        $this->defaultController = $defaultController;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
        return $this->lang;
    }

    /**
     * @return Connect
     */
    public function getDB(): Connect
    {
        if (is_null($this->db)) {
            $this->db = new Connect($this->dbDsn, $this->dbUser, $this->dbPassword, $this->dbOptions);
        }

        return $this->db;
    }

    /**
     * @param string $path
     * @param bool $create
     * @throws Exception
     * @return string
     */
    public function getPath(string $path, $create = false): string
    {
        if ($create && !file_exists($path)) {
            if (mkdir($path, 0777, true)) {
                return $path;
            } else {
                throw new Exception(Sys::mId('error', 'exceptionFailedCreateDirectory', ['path' => $path]));
            }
        }
        return $path;
    }

    /**
     * @param string $path
     * @return bool
     */
    public function deletePath(string $path): bool
    {
        $endSymbol = substr($path, -1);
        if ($endSymbol !== '/' && $endSymbol !== '\\') {
            $path .= '/';
        }
        if (!file_exists($path)) {
            return true;
        }
        foreach (glob($path . '*') as $file) {
            if (is_dir($file)) {
                if (!self::deletePath($file)) {
                    return false;
                }
            } elseif (!unlink($file)) {
                return false;
            }
        }
        if (!rmdir($path)) {
            return false;
        }
        return true;
    }

    /**
     * @return Request|null
     */
    public function getRequest(): ?Request
    {
        if (is_null($this->request))
            $this->request = new Request();

        return $this->request;
    }

    /**
     * @param string $lang
     * @return bool
     */
    public function setLanguage(string $lang): bool
    {
        if (array_search($lang, $this->getSupportLanguages(), true) !== false) {
            $this->lang = $lang;
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getSupportLanguages(): array
    {
        return [
            'en-US',
            'ru-RU',
        ];
    }

    /**
     * @return string
     */
    public function getDefaultLanguage(): string
    {
        return $this->defaultLang;
    }

    /**
     * @return array
     */
    public function getLog(): array
    {
        return $this->log;
    }

    /**
     * @param mixed $data
     */
    public function addLog($data): void
    {
        array_push($this->log, $data);
    }
}