<?php
declare(strict_types=1);

namespace CDS;
use PDO;

class Database {
    private static $instance;
    private PDO $connection;

    /**
     * Get database connection instance
     * @return static
     */
    public static function getInstance() : self {
        if ( static::$instance == null ) {
            static::$instance = new self();
            (static::$instance)->connect();
        }
        return static::$instance;
    }

    /**
     * Connect to database based on config settings
     * @return bool
     * @throws \Exception
     */
    public function connect() : bool {
        $config = Config::getInstance();
        $connectionString = $config->getSetting('Database.connection_string');
        $username = $config->getSetting('Database.username');
        $password = $config->getSetting('Database.password');
        try {
            $this->connection = new PDO($connectionString, $username, $password);
            $this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    
        } catch (\PDOException $e) {
            echo 'Error connecting to Database: ' . $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * Get PDO Object
     * @return PDO
     */
    public function getConnection() : PDO {
        return $this->connection;
    }
}