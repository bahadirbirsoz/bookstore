<?php


defined('APP_PATH') || define('APP_PATH', dirname(__DIR__));
defined('DS') || define('DS', DIRECTORY_SEPARATOR);

include APP_PATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'bootstrap.php';

$app = Bookstore\Util\App::getInstance();
$app->run();


