<?php
// 数据库连接类
class Database {
    private static $pdo;
    
    public static function getConnection() {
        if (!isset(self::$pdo)) {
            $config = require __DIR__ . '/../config/config.php';
            try {
                self::$pdo = new PDO($config['database']['dsn']);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('数据库连接失败: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
    
    public static function query($sql, $params = []) {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public static function lastInsertId() {
        return self::getConnection()->lastInsertId();
    }
}
