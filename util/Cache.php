<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 5.06.2018
 * Time: 12:19
 */

namespace Bookstore\Util;


abstract class Cache
{
    protected abstract function connect();

    protected abstract function disconnect();

    protected abstract function set($key, $value);

    protected $ttl = 3600;

    /**
     * @param $value String
     * @return String
     */
    protected abstract function get($value);

    /**
     * @param $key String
     * @return mixed
     */
    protected abstract function remove($key);

    /**
     * @param $key String
     * @return bool
     */
    protected abstract function exists($key);

    public final function __get($key)
    {
        return $this->get($key);
    }

    public final function __set($key, $value)
    {
        return $this->set($key, $value);
    }

    public final function __isset($key)
    {
        return $this->exists($key);
    }

    public function __construct()
    {
        $this->connect();
    }

    public function __destruct()
    {
        $this->disconnect();
    }

    public function setTimeout($timeoutInSeconds)
    {
        $this->ttl = $timeoutInSeconds;
    }

}