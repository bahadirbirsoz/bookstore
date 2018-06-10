<?php

namespace Bookstore\Util;

use Bookstore\Util\App\Auth;
use Bookstore\Util\App\Db;
use Bookstore\Util\App\Router;
use Bookstore\Util\App\Session;
use Bookstore\Util\App\Validator;
use Bookstore\Util\Cache\Redis;
use Bookstore\Util\Http\Request;
use Bookstore\Util\Http\Response;
use Bookstore\Util\Mvc\Controller;
use Bookstore\Util\Mvc\View;

class App
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var View
     */
    private $view;

    /**
     * @var App
     */
    static $instance;

    /**
     * @var
     */
    private $db;

    /**
     * @var Cache
     */
    private $cache;


    /**
     * @var Auth
     */
    private $auth;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @return App
     */
    public static function getInstance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->router = new Router();
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    public function run()
    {
        $this->runController();
        $this->lockDatabase();
        $this->runView();
    }

    protected function runController()
    {
        $matchedRoute = $this->getRouter()->getMatchRoute();

        $className = $matchedRoute->getRouteClassName();
        $actionName = $matchedRoute->getRouteActionName();
        $arguments = $matchedRoute->getArguments();
        try {
            $this->controller = new $className();
        } catch (\Exception $e) {
            $matchedRoute = $this->getRouter()->getNotFoundRoute();
            $className = $matchedRoute->getRouteClassName();
            $actionName = $matchedRoute->getRouteActionName();
            $this->controller = new $className();
        }

        if (count($arguments)) {
            $this->controller->$actionName($arguments);
        } else {
            $this->controller->$actionName();
        }
    }

    protected function lockDatabase()
    {
        defined("BOOKSTORE_DB_LOCKED") || define("BOOKSTORE_DB_LOCKED", TRUE);
    }

    protected function runView()
    {
        

        $matchedRoute = $this->getRouter()->getMatchRoute();
        $this->view->setView($matchedRoute->getController(), $matchedRoute->getAction());
        $this->view->render();
    }

    /**
     * @return mixed
     */
    public function getDb()
    {
        if (!isset($this->db)) {
            $this->db = new Db(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        }
        return $this->db;
    }

    /**
     * @return View
     */
    public function getView()
    {
        if (!isset($this->view)) {
            $this->view = new View();
        }
        return $this->view;
    }

    /**
     * @return Cache|Redis
     */
    public function getCache()
    {
        if (!isset($this->cache)) {
            $this->cache = new Cache\Redis();
        }
        return $this->cache;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        if (!isset($this->request)) {
            $this->request = new Request();
        }
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        if (!isset($this->response)) {
            $this->response = new Response();
        }
        return $this->response;
    }

    /**
     * @return Auth
     */
    public function getAuth()
    {
        if (!isset($this->auth)) {
            $this->auth = new Auth();
        }
        return $this->auth;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        if (!isset($this->session)) {
            $this->session = new Session();
        }
        return $this->session;
    }

    public function getValidator()
    {
        if (!isset($this->validator)) {
            $this->validator = new Validator();
        }
        return $this->validator;
    }
}