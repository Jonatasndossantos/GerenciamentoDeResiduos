<?php
namespace App\Config;

class Database {
    private static $instance = null;
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new \mysqli('localhost', 'root', '', 'Pesagem');
            if (self::$instance->connect_error) {
                throw new \Exception("Erro na conexão: " . self::$instance->connect_error);
            }
        }
        return self::$instance;
    }
} 