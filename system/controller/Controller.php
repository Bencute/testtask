<?php


namespace system\controller;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Throwable;
use Sys;
use system\application\Application;
use system\application\Router;
use system\exception\ControllerException;
use system\view\View;

/**
 * Base class Controller
 * Наименование метода для Action должно быть в формате "actionNameAction"
 *
 * @package system
 */
abstract class Controller
{
    /**
     * @var Application
     */
    public Application $app;

    /**
     * @var string
     */
    public string $nameClassView = 'system\view\View';

    /**
     * @var string
     */
    public string $pathToViews = 'view';

    /**
     * @var string
     */
    public string $pathToLayouts = 'layout';
    /**
     * @var string
     */
    public string $layout = 'main';

    /**
     * @var string
     */
    public string $defaultAction = 'index';

    /**
     * @var string
     */
    public ?string $pathToController = null;

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * Controller constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Application
     */
    public function getApp(): Application
    {
        return $this->app;
    }

    /**
     * @param string|null $action
     * @throws Exception
     * @return string
     */
    public function runAction(?string $action): string
    {
        if (is_null($action))
            $action = $this->getDefaultAction();

        $actionMethodName = $this->getActionMethodName($action);

        if (!method_exists($this, $actionMethodName))
            throw new ControllerException(Sys::mId('error', 'exceptionActionNotFound', ['actionMethodName' => $actionMethodName]));

        $args = $this->bindActionParams($actionMethodName);
        return call_user_func_array([$this, $actionMethodName], $args);
    }

    /**
     * @param $viewName
     * @param array $params
     * @throws Throwable
     * @return string
     */
    public function render($viewName, array $params = []): string
    {
        /** @var View $view */
        $view = new $this->nameClassView($viewName, $this);
        return $view->render($params);
    }

    /**
     * @param $action
     * @throws ReflectionException
     * @throws Exception
     * @return array
     */
    private function bindActionParams($action)
    {
        $params = $_GET;
        $method = new ReflectionMethod($this, $action);

        $args = [];
        $missing = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                $isValid = true;
                if ($param->isArray()) {
                    $params[$name] = (array) $params[$name];
                } elseif (is_array($params[$name])) {
                    $isValid = false;
                } elseif (
                    ($type = $param->getType()) !== null &&
                    $type->isBuiltin() &&
                    ($params[$name] !== null || !$type->allowsNull())
                ) {
                    $typeName = $type->getName();
                    switch ($typeName) {
                        case 'int':
                            $params[$name] = filter_var($params[$name], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
                            break;
                        case 'float':
                            $params[$name] = filter_var($params[$name], FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);
                            break;
                    }
                    if ($params[$name] === null) {
                        $isValid = false;
                    }
                }
                if (!$isValid) {
                    throw new Exception(Sys::mId('error', 'exceptionSendInvalidTypeDataForParameter', ['nameParam' => $name]));
                }
                $args[] = $params[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            $strParams = implode(', ', $missing);
            throw new Exception(Sys::mId('error', 'exceptionMissingRequiredParameters', ['params' => $strParams]));
        }

        return $args;
    }

    /**
     * @param $action
     * @return string
     */
    public function getActionMethodName($action): string
    {
        $action = Router::normalizeRouteParams($action);
        return 'action' . $action;
    }

    /**
     * @return string
     */
    public function getPathToViews(): string
    {
        return Sys::getBaseDir() . '/' . $this->pathToViews . '/' . $this->getId();
    }

    /**
     * @return string
     */
    public function getPathToLayout(): string
    {
        return Sys::getBaseDir() . '/' . $this->pathToViews . '/' . $this->pathToLayouts . '/' . $this->layout . '.php';
    }

    /**
     * @throws ReflectionException
     * @return string
     */
    public function getPathToController(): string
    {
        if (is_null($this->pathToController)) {
            $rc = new ReflectionClass(get_class($this));
            $this->pathToController = dirname($rc->getFileName());
        }

        return $this->pathToController;
    }

    /**
     * Возвращает идентификатор контроллера в нижнем регистре
     *
     * @return string
     */
    public function getId(): string
    {
        $aNameClass = explode('\\', get_class($this));
        $nameClass = $aNameClass[array_key_last($aNameClass)];
        return strtolower(
            substr(
                $nameClass,
                0,
                strlen($nameClass) - 1 - strpos($nameClass, 'Controller') - 2)
        );
    }

    /**
     * @return string
     */
    public function getDefaultAction(): string
    {
        return $this->defaultAction;
    }

    /**
     * @param string $defaultAction
     */
    public function setDefaultAction(string $defaultAction): void
    {
        $this->defaultAction = $defaultAction;
    }

    /**
     * Перенаправляет пользователя по переданному урл
     *
     * @param $url
     * @return bool
     */
    public function redirect($url): bool
    {
        header('Location: ' . $url);
        return true;
    }
}