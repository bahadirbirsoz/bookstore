<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8.06.2018
 * Time: 15:38
 */

namespace Bookstore\Util\App;


use Bookstore\Util\App;
use Bookstore\Util\Cache;
use Bookstore\Util\Http\Request;
use Bookstore\Util\Http\Response;
use Bookstore\Util\Mvc\View;
use Bookstore\Util\Security\Csrf;

/**
 * Class Component
 * @package Bookstore\Util\App
 * @property Auth $auth
 * @property Session $session
 * @property Response $response
 * @property Request $request
 * @property Validator $validator
 * @property Cache\Redis $cache
 * @property View $view
 * @property Csrf $csrf
 */
class Component
{

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $service = $this->getService($name);
        }
        if (!$service) {
            throw New \Exception("Undefined Service '$name'");
        }
        return $service;
    }

    public function getService($name)
    {
        switch ($name) {
            case "auth":
                return App::getInstance()->getAuth();
            case "view":
                return App::getInstance()->getView();
            case "request":
                return App::getInstance()->getRequest();
            case "response":
                return App::getInstance()->getResponse();
            case "session":
                return App::getInstance()->getSession();
            case "validator":
                return App::getInstance()->getValidator();
            case "cache":
                return App::getInstance()->getCache();
            case "csrf":
                return App::getInstance()->getCsrf();
        }
        return null;
    }

}