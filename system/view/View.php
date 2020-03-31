<?php


namespace system\view;


use Exception;
use system\controller\Controller;
use Throwable;

/**
 * Class View
 * @package system\view
 */
class View
{
    /**
     * @var Controller
     */
    public Controller $controller;

    /**
     * @var string
     */
    public $viewName;

    /**
     * View constructor.
     * @param $viewName
     * @param Controller $controller
     */
    public function __construct($viewName, Controller $controller)
    {
        $this->controller = $controller;

        $viewName = trim($viewName, "\\/");
        $this->viewName = $viewName;
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Возвращает путь до view файла
     * @return string
     */
    public function getPathToViewFile()
    {
        return $this->controller->getPathToViews() . '/' . $this->viewName . '.php';
    }

    /**
     * @return string
     */
    public function getPathToLayoutFile()
    {
        return $this->controller->getPathToLayout();
    }

    /**
     * @param $params
     * @throws Throwable|Exception
     * @return string
     */
    public function render(array $params = [])
    {
        $contentView = $this->renderFile($this->getPathToViewFile(), $params);
        return $this->renderFile($this->getPathToLayoutFile(), ['content' => $contentView]);
    }

    /**
     * @param array $params
     * @throws Throwable
     * @return string
     */
    public function renderPartial(array $params = []): string
    {
        return $this->renderFile($this->getPathToViewFile(), $params);
    }

    /**
     * @param string $viewName
     * @param array $params
     * @throws Throwable
     * @return string
     */
    public function renderView(string $viewName, array $params = []): string
    {
        $view = new static($viewName, $this->getController());
        return $view->renderPartial($params);
    }

    /**
     * @param $file
     * @param array $params
     * @throws Throwable
     * @return false|string
     */
    public function renderFile($file, array $params = [])
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $file;
            return ob_get_clean();
        } catch (Exception $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw $e;
        }
    }
}