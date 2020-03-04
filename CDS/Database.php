<?php
declare(strict_types=1);

namespace CDS;
use PDO;

class Database {
    private static $instance;
    private PDO $connection;
    
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
        }
        return static::$instance;
    }
    
    public function connect() : bool {
        $config = Config::getInstance();
        $connectionString = $config->getSetting('Database.connection_string');
        $username = $config->getSetting('Database.username');
        $password = $config->getSetting('Database.password');
        try {
            $this->connection = new PDO($connectionString, $username, $password);
        } catch (\PDOException $e) {
            echo 'Error connecting to Database: ' . $e->getMessage();
            return false;
        }
        return true;
    }
    
    public function getConnection() : PDO {
        return $this->connection;
    }
}