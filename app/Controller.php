<?php

namespace App;

use App\View;

class Controller
{
    private $controllerMethod = null;
    private $controllerClass = null;
    private $controllerNamespace = 'Controllers';

    /**
     * Renders layout with parameters
     *
     * @param string $layoutPath
     * @param array $layoutParams
     */
    public function renderLayout(string $layoutPath, array $layoutParams = [])
    {
        View::render($layoutPath, $layoutParams);
    }

    /**
     * Returns request object
     *
     * @return Request
     */
    public function getRequest()
    {
        return new Request();
    }

    /**
     * Saves controller instance
     *
     * @param string $className
     */
    public function setInstance(string $className)
    {
        $controllerClass = $this->controllerNamespace . '\\' . $className;

        if (!class_exists($controllerClass)) {
            throw new \RuntimeException('Controller not found');
        }

        $this->controllerClass = new $controllerClass();
    }

    /**
     * Saves the controller method
     *
     * @param string $methodName
     */
    public function setMethod(string $methodName)
    {
        if (is_null($this->controllerClass)) {
            throw new \RuntimeException('Controller was not set');
        }

        if (!method_exists($this->controllerClass, $methodName)) {
            throw new \RuntimeException('No such method as "' . $methodName . '"');
        }

        if (!is_callable([$this->controllerClass, $methodName])) {
            throw new \RuntimeException('Method "' . $methodName . '" cannot be called');
        }

        $this->controllerMethod = $methodName;
    }

    /**
     * Calls the stored controller method
     *
     * @param array $args
     */
    public function dispatch(array $args)
    {
        if (is_null($this->controllerClass) || is_null($this->controllerMethod)) {
            throw new \RuntimeException('Something went wrong');
        }

        call_user_func_array(
            [
                $this->controllerClass,
                $this->controllerMethod
            ],
            $args
        );
    }
}