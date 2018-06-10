<?php

namespace Bookstore\Util\App;

class Router
{
    /**
     * @var Route[]
     */
    protected $routes = [];

    protected $defaultNamespace;

    /**
     * @var \Bookstore\Util\App\Route
     */
    protected $matchedRoute = null;

    protected $notFoundRoute;

    /**
     * @param $route \Bookstore\Util\App\Route
     */
    public function register($route)
    {
        if (!$route->isNamespaceSet()) {
            $route->setNamespace($this->defaultNamespace);
        }
        $this->routes[] = $route;
    }

    protected function match()
    {
        $uriArr = $this->getUriArgs();

        foreach ($this->routes as $route) {

            $pattern = $route->getPattern();

            $patternArr = explode("/", $pattern);
            if ($this->isMatch($patternArr, $uriArr)) {
                $this->matchedRoute = $route;
                $this->matchedRoute->applyUri($uriArr);
                break;
            }
        }

        if ($this->matchedRoute == null) {
            $this->matchedRoute = $this->notFoundRoute;
        }
    }

    public function setDefaultNamespace($namespace)
    {
        $this->defaultNamespace = $namespace;
    }

    /**
     * @param $route Route
     * @return $this
     */
    public function setNotFoundRoute($route)
    {
        $this->register($route);
        $this->notFoundRoute = $route;
        return $this;
    }

    /**
     * @return \Bookstore\Util\App\Route
     */
    public function getMatchRoute()
    {
        if ($this->matchedRoute === null) {
            $this->match();
        }
        return $this->matchedRoute;
    }


    protected function getUriArgs()
    {
        $uri = ltrim($_SERVER['REQUEST_URI'], '/');
        return explode('/', $uri);
    }

    protected function isMatch($patternArr, $uriArr)
    {
        $countEqual = count($patternArr) == count($uriArr);
        $countExceeds = count($patternArr) < count($uriArr);
        $patternHasParams = array_search(':params', $patternArr) !== false;
        if (!(($countEqual) || ($patternHasParams && $countExceeds))) {
            return false;

        }
        foreach ($patternArr as $index => $pattern) {
            if (!($pattern == $uriArr[$index] || substr($pattern, 0, 1) == ":")) {
                return false;
            }
        }
        return true;
    }

    public function getNotFoundRoute()
    {
        return $this->notFoundRoute;
    }


}