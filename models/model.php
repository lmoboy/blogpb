<?php
require "database.php";

abstract class Model
{
    protected static $db;

    public static function init()
    {
        if (!self::$db) {
            self::$db = new Database();
        }
    }
    abstract protected static function getTableName(): string;
    
    public static function all()
    {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName();

        $records = self::$db->query($sql)->fetchAll();
        return $records;
    }


}