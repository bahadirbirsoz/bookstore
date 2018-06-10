<?php

namespace Bookstore\Util\Cache;


use Bookstore\Util\Cache;

class Redis extends Cache
{

    protected $redis;

    protected function connect()
    {
        $this->redis = new \Redis();
        $this->redis->connect('127.0.0.1', 6379);

    }

    protected function disconnect()
    {
        $this->redis->close();
    }

    public function set($key, $value)
    {
        return $this->redis->set($key, $value, Array('nx', 'ex' => $this->ttl));
    }

    /**
     * @param $key String
     * @return String
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * @param $key String
     * @return mixed
     */
    public function remove($key)
    {
        return $this->redis->delete($key); /* return 2 */

    }

    /**
     * @param $key String
     * @return bool
     */
    public function exists($key)
    {
        return $this->redis->exists($key); /* 1 */
    }
}