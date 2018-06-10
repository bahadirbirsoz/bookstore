<?php

namespace Bookstore\Util\Mvc;


use Bookstore\Util\App;

/**
 * Class Controller
 * @package Bookstore\Util\Mvc
 * @property $view \Bookstore\Util\Mvc\View
 */
abstract class Controller extends App\Component
{
    /**
     * @var App
     */
    protected $app;

    public final function __construct()
    {
        $this->app = App::getInstance();
        $this->initialize();
    }

    abstract function initialize();
}