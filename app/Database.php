<?php

namespace App;

use App\Interfaces\Singleton;

class Database extends Singleton
{
    protected $_connection = null;

    /** @var Config $config */
    protected $_config = null;

    protected function __construct()
    {
        $this->_config = Config::getInstance();
        $this->_connection = $this->getConnection();
    }

    private function getConnection()
    {
        if (is_null($this->_connection)) {
            $this->_connection = $this->createConnection();
        }

        return $this->_connection;
    }

    private function createConnection()
    {
        $databaseType = $this->_config->get("database.default");
        $db = $this->_config->get("database.$databaseType.database");
        $user = $this->_config->get("database.$databaseType.user");
        $pass = $this->_config->get("database.$databaseType.password");
        $host = $this->_config->get("database.$databaseType.host");
        $port = $this->_config->get("database.$databaseType.port");

        try {
            $dsn = $databaseType . ':host=' . $host . ';port=' . $port . ';dbname=' . $db;
            return new \PDO($dsn, $user, $pass);
        } catch (PDOException $e) {
            throw new \Exception("Can't connect to database: " . $e->getMessage());
        }
    }

    public function query(string $sql, array $params = [])
    {
        try {
            $query = $this->_connection->prepare($sql);

            if (!empty($params)) {
                foreach ($params as $param => $val) {
                    $query->bindParam($param, $val);
                }
            }

            $query->execute();
            return $query;
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
}