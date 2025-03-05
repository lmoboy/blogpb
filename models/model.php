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

    public static function find($data)
    {
        self::init();
        $sql = "SELECT * FROM " . static::getTableName() . " WHERE content LIKE %$data%";

        $records = self::$db->query($sql)->fetchAll();
        return $records;
    }

    public static function yeet($id)
    {
        $id = $id['id'];
        self::init();
        $sql = "DELETE FROM " . static::getTableName() . " WHERE id = $id";
        $records = self::$db->query($sql)->fetchAll();
        return $records;
    }

    public static function bake($data)
    {
        if (!isset($data['content']) || empty(trim($data['content']))) {
            throw new Exception('Content is required');
        }

        $content = $data['content'];
        self::init();
        
        try {
            $sql = "INSERT INTO " . static::getTableName() . "(content) VALUES (?)"; 
            $stmt = self::$db->query($sql);
            $stmt->execute([$content]);
            
            return ['success' => true, 'message' => 'Post created successfully'];
        } catch (Exception $e) {
            throw new Exception('Failed to create post: ' . $e->getMessage());
        }
    }

    public static function sprinkle($id, $data)
    {
        self::init();
        $sql = "UPDATE " . static::getTableName() . " SET content = ? WHERE id = ?";
        $records = self::$db->query($sql, [$data, $id])->fetchAll();
        return $records;
    }



}