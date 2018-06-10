<?php

namespace Bookstore\Util\App;


class Route
{

    protected $namespace = null;

    protected $controller = null;

    protected $action = null;

    protected $arguments = null;

    protected $paramArr = [];


    /**
     * @param $pattern Regex pattern
     * There are four variables that can be used in router :
     * :controller
     * :action
     * :params
     * :param
     */
    protected function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param $pattern
     * @return Route
     */
    public static function _($pattern)
    {
        return new Route($pattern);
    }

    /**
     * @return bool
     */
    public function isNamespaceSet()
    {
        return $this->namespace !== null;
    }

    /**
     * @param $namespace String
     * @return $this
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @param $controller String
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * @param $action
     * @return $this
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getPattern()
    {
        return $this->pattern;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param $param
     * @return $this
     */
    public function addParam($param)
    {
        $this->paramArr[] = $param;
        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->paramArr = $params;
        return $this;
    }


    public function applyUri($uriArr)
    {
        $patternArr = explode("/", $this->pattern);
        foreach ($patternArr as $index => $patternArg) {
            switch ($patternArg) {
                case ':controller':
                    $this->setController($uriArr[$index]);
                    break;
                case ':action':
                    $this->setAction($uriArr[$index]);
                    break;
                case ':namespace':
                    $this->setNamespace($uriArr[$index]);
                    break;
                case ':param':
                    $this->addParam($uriArr[$index]);
                    break;
                case ':params':
                    $this->setParams(array_slice($uriArr, $index));
                    break 2;
            }
        }
    }

    public function getRouteClassName()
    {
        $controllerName = str_replace('-', '', ucwords($this->controller, '-')) . 'Controller';
        return $this->namespace . '\\' . $controllerName;
    }

    public function getRouteActionName()
    {
        return strtolower($this->getAction()) . 'Action';
    }

    public function getArguments()
    {
        return $this->paramArr;

    }

}