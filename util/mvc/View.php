<?php

namespace Bookstore\Util\Mvc;

use Bookstore\Util\App;

class View
{
    protected $viewFiles = [];
    protected $basePath;
    protected $controllerPage = "main";

    protected $viewArgs = [];

    static $level = 0;

    public function __construct()
    {
        $this->basePath = APP_PATH . DS . "view" . DS;
    }

    public function __set($name, $value)
    {
        $this->viewArgs[$name] = $value;
    }

    public function setMasterPage($masterPage)
    {
        $path = $this->basePath . 'master' . DS . $masterPage . '.phtml';
        if (file_exists($path)) {
            $this->viewFiles[1] = $path;
        }
        return $this;
    }

    public function setLayout($layoutPage)
    {
        $path = $this->basePath . 'layout' . DS . $layoutPage . '.phtml';
        if (file_exists($path)) {
            $this->viewFiles[2] = $path;
        }
        return $this;
    }

    public function setController($controllerName)
    {
        $path = $this->basePath . $controllerName . '.phtml';
        if (file_exists($path)) {
            $this->viewFiles[3] = $path;
        }
        return $this;
    }

    public function setAction($controllerName, $actionName)
    {
        $path = $this->basePath . $controllerName . DS . $actionName . '.phtml';
        if (file_exists($path)) {
            $this->viewFiles[4] = $path;
        }
        return $this;
    }

    public function render()
    {

        extract($this->viewArgs);
        $render = function () {
            App::getInstance()->getView()->render();
        };
        static::$level++;
        for ($i = static::$level; $i <= 4; $i++) {
            if (isset($this->viewFiles[$i]) && file_exists($this->viewFiles[$i])) {
                static::$level = $i;
                include $this->viewFiles[$i];
                return;
            }
        }
    }


    public function setView($controllerName, $actionName)
    {
        $this->setAction($controllerName, $actionName);
        $this->setController($controllerName);
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->viewArgs)) {
            return $this->viewArgs[$name];
        }
        return $name;
    }

    public function setDefaultMasterPage($masterPageName)
    {
        if (!array_key_exists(1, $this->viewFiles)) {
            $this->setMasterPage($masterPageName);
        }
    }

    public function setDefaultLayoutPage($layoutName)
    {
        if (!array_key_exists(2, $this->viewFiles)) {
            $this->setLayout($layoutName);
        }
    }

}

/**
 * 1. main layout
 * 2. controller layout
 * 3. action page
 */