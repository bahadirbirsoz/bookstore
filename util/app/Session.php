<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7.06.2018
 * Time: 09:33
 */

namespace Bookstore\Util\App;


class Session
{
    public function __construct()
    {
        session_start();
    }

    public function get($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function delete($key)
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    public function clear()
    {
        $_SESSION = [];
        session_destroy();
    }

    public function getSessionId()
    {
        return session_id();
    }

}