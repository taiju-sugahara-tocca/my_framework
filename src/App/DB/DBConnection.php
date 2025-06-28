<?php
namespace App\DB;

use App\Config\DBConfig;
use PDO;
use PDOException;

class DBConnection
{
    private static ?PDO $connection = null;

    private function __construct() {} // 外部からnewできない

    public static function getConnection(): PDO
    {
        if (self::$connection === null) { //singletonパターン
            try {
                $config = DBConfig::getConfig();
                self::$connection = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']};port={$config['port']}",
                    $config['user'],
                    $config['password']
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                throw new \RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
    
}