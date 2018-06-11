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
use Bookstore\Util\Security\Csrf;

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
     * @var Csrf
     */
    private $csrf;

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
     * @var Route
     */
    protected $activeRoute;

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

    protected function route()
    {
        $className = $this->activeRoute->getRouteClassName();
        $actionName = $this->activeRoute->getRouteActionName();
        $arguments = $this->activeRoute->getArguments();
        try {
            $this->controller = new $className();
        } catch (\Exception $e) {
            $this->activeRoute = $this->getRouter()->getNotFoundRoute();
            return $this->route();
        }
        $response = call_user_func_array([$this->controller, $actionName], $arguments);
        if ($response && is_object($response) && get_class($response) == "Bookstore\Util\App\Route") {
            $this->activeRoute = $response;
            return $this->route();
        }

    }

    protected function runController()
    {
        $this->activeRoute = $this->getRouter()->getMatchRoute();
        $this->route();
    }

    protected function lockDatabase()
    {
        defined("BOOKSTORE_DB_LOCKED") || define("BOOKSTORE_DB_LOCKED", TRUE);
    }

    protected function runView()
    {


        $this->view->setView($this->activeRoute->getController(), $this->activeRoute->getAction());
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

    public function getCsrf()
    {
        if (!isset($this->csrf)) {
            $this->csrf = new Csrf();
        }
        return $this->csrf;
    }


}