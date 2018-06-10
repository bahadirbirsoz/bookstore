<?php

namespace Bookstore\Util\App;


class Db
{

    /**
     * @var Db
     */
    static $instance;

    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @return Db
     */

    public function __construct($host, $db, $user, $pass)
    {
        if (!defined("BOOKSTORE_DB_LOCKED")) {
            $this->conn = new \PDO('mysql:host=' . $host . ';dbname=' . $db . ";charset=utf8", $user, $pass);
        }
    }

    /**
     * @return PDO
     */
    public function getConnection()
    {
        if (!defined("BOOKSTORE_DB_LOCKED")) {
            return $this->conn;
        }
    }

}

