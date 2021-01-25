<?php

namespace App\Core\Database;
use PDOException;
use PDO;
class Connection
{
    public static function make($config)
    {   
        $host = '127.0.0.1';
        $db   = 'currency_convert_dbase';
        $user = $config['username'];
        $pass = $config['password'];
        $port = $config['port'];
        $charset = 'utf8mb4';
    
        $options = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";

        try {
            return new PDO(
                $dsn, $user, $pass, $options
            );
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
}