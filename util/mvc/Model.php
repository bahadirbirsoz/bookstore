<?php

namespace Bookstore\Util\Mvc;


use Bookstore\Util\App;

class Model
{
    public static function getTable()
    {
        $arr = explode("\\", get_called_class());
        return strtolower(array_pop($arr));
    }

    public static function camelToSnake($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }


    public static function __callStatic($name, $arguments)
    {
        if (substr($name, 0, 6) == "findBy") {
            $key = static::camelToSnake(substr($name, 6));
            return static::find([
                $key => $arguments[0]
            ]);
        }
        if (substr($name, 0, 9) == "findOneBy") {
            $key = static::camelToSnake(substr($name, 9));
            return static::findOne([
                $key => $arguments[0]
            ]);
        }
    }


    public static function getPK()
    {
        return static::getTable() . "_id";
    }

    public static function where($data)
    {

        if (count($data) == 0) {
            return "";
        }
        if (array_key_exists("conditions", $data)) {
            return " WHERE " . $data["conditions"] . " ";
        } else {
            return " WHERE `" . implode("`=? AND `", array_keys($data)) . "`=? ";
        }
    }

    /**
     * @param array $data
     * @return static[]
     */
    public static function find($data = [])
    {
        if (is_numeric($data)) {
            return static::findOne([
                static::getPK() => $data
            ]);
        }
        $sql = "SELECT * FROM `" . static::getTable() . "` " . static::where($data);

        $cacheLayer = App::getInstance()->getCache();
        $cacheKey = md5($sql . serialize($data));

        if ($cacheLayer->exists($cacheKey) && !defined("DEBUG_MODE")) {
            die("using cache");
            return unserialize($cacheLayer->get($cacheKey));
        }
        $sth = static::getConnection()->prepare($sql);
        $sth->execute(static::conditions($data));
        $result = $sth->fetchAll(\PDO::FETCH_CLASS, get_called_class());
        $cacheLayer->set($cacheKey, serialize($result));
        return $result;
    }

    public static function select($table, $data = [], $fetchMethod = \PDO::FETCH_OBJ)
    {

        if (is_array($table)) {
            $data = $table;
            $table = static::getTable();
        }

        if (stristr($table, " from ")) {
            $sql = $table . " " . static::where($data);
        } else {
            $sql = "SELECT * FROM `" . $table . "` " . static::where($data);
        }
        $sth = static::getConnection()->prepare($sql);

        $sth->execute(static::conditions($data));
        return $sth->fetchAll($fetchMethod);
    }

    public static function select1d($table, $data = [])
    {
        $data = static::select($table, $data, \PDO::FETCH_ASSOC);
        if (count($data) == 0) {
            return [];
        }
        $keys = array_keys($data[0]);
        $result = [];
        foreach ($data as $row) {
            $result[$row[$keys[0]]] = $row[$keys[1]];
        }
        return $result;
    }

    public static function conditions($data = [])
    {
        if (!count($data)) {
            return [];
        }

        if (array_key_exists("bind", $data)) {
            return $data['bind'];
        }
        $bind = [];
        foreach ($data as $key => $val) {
            $bind[] = $val;
        }
        return $bind;
    }


    public static function findOne($data = [])
    {
        $arr = static::find($data);
        if (!$arr) {
            return false;
        }
        return $arr[0];
    }

    public static function findById($id)
    {
        return self::findOne([static::getPK() => $id]);
    }


    private function insertQueryString($data)
    {
        $keys = array_keys($data);
        return "INSERT INTO `" . static::getTable() . "` " . static::getInsertCols($keys) . " VALUES " . static::getInsertValues($keys);
    }

    private static function getInsertCols($keys)
    {
        return "(`" . implode("`,`", $keys) . "`)";
    }

    private static function getInsertValues($keys)
    {
        return "( :" . implode(",:", $keys) . " )";
    }

    private function updateQueryString($data)
    {
        $pk = static::getPK();
        unset($data[$pk]);
        $keys = array_keys($data);
        $update = "UPDATE `" . static::getTable() . "` SET ";

        foreach ($keys as $key) {
            $update .= "`$key`=:$key ,";
        }
        $update = rtrim($update, ",");
        $update .= " WHERE `$pk` = :$pk ";
        return $update;
    }

    public function save()
    {
        if (!isset($this->{static::getPK()})) {
            return $this->insert();
        }

        $currentRec = static::findById($this->{static::getPK()});
        if ($currentRec) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }


    private function update()
    {
        $data = get_object_vars($this);
        $sql = self::updateQueryString($data);

        $sth = static::getConnection()->prepare($sql);

        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }
        if (method_exists($this, 'beforeUpdate')) {
            $this->beforeUpdate();
        }


        if ($sth->execute($data) === false) {
            return false;
        }

        if (method_exists($this, 'afterUpdate')) {
            $this->afterUpdate();
        }
        if (method_exists($this, 'afterSave')) {
            $this->afterSave();
        }

        return true;
    }

    public function insert()
    {
        $data = get_object_vars($this);
        $sql = self::insertQueryString($data);
        $sth = static::getConnection()->prepare($sql);

        if (method_exists($this, 'beforeSave')) {
            $this->beforeSave();
        }
        if (method_exists($this, 'beforeInsert')) {
            $this->beforeInsert();
        }

        if ($sth->execute($data) === false) {
            return false;
        }
        $this->{static::getPK()} = static::getConnection()->lastInsertId();


        if (method_exists($this, 'afterInsert')) {
            $this->afterInsert();
        }
        if (method_exists($this, 'afterSave')) {
            $this->afterSave();
        }

        return true;
    }


    public function remove()
    {
        return static::delete([
            static::getPK() => $this->{static::getPK()}
        ]);
    }

    public static function delete($data = [])
    {
        if (is_numeric($data)) {
            $data = [
                static::getPK() => $data
            ];
        }
        $sql = "DELETE FROM " . static::getTable() . " " . static::where($data);

        $sth = static::getConnection()->prepare($sql);
        $sth->execute(static::conditions($data));
        $count = $sth->rowCount();
        return $count > 0;
    }

    public static function getConnection()
    {
        return App::getInstance()->getDb()->getConnection();
    }


}

