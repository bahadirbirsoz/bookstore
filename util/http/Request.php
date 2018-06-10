<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8.06.2018
 * Time: 15:03
 */

namespace Bookstore\Util\Http;


class Request
{

    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isPost()
    {
        return $this->getMethod() == "POST";
    }

    public function isGet()
    {
        return $this->getMethod() == "GET";
    }

    public function hasFiles()
    {
        var_dump($_FILES);
    }

    public function getFiles()
    {

    }

    public function hasPost(...$args)
    {
        foreach ($args as $arg) {
            if (!isset($_POST[$arg])) {
                return false;
            }
        }
        return true;
    }

    public function getPost($key = null)
    {
        if ($key === null) {
            return $_POST;
        }
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        throw new \Exception("Invalid Post Data");
    }

    public function getUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }


}