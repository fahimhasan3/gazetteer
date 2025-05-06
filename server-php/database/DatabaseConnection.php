<?php

require_once 'MySQLConnection.php';
require_once 'PgSQLConnection.php';

abstract class DatabaseConnection {
    protected $config;
    protected $connection;
    protected $query_count = 0;

    public function __construct($config) {
        $this->config = $config;
        $this->connect();
    }

    /**
     * Establish a connection to the database
     */
    abstract protected function connect();

    /**
     * Prepare and execute a query with optional parameters
     */
    abstract public function query(string $query, ...$params);

    /**
     * Fetch all rows from the last executed query (assoc array)
     */
    abstract public function fetchAll();

    /**
     * Fetch single row from the last executed query
     */
    abstract public function fetchOne();

    abstract public function affectedRows();

    /**
     * Get the ID of the last inserted row
     */
    abstract public function lastInsertId();

    /**
     * Close the connection if needed
     */
    abstract public function close();

    /**
     * Get number of queries executed
     */
    public function getQueryCount(): int {
        return $this->query_count;
    }

    abstract protected function error($message);

    /**
     * Static factory method for building the right DB connection type
     */
    public static function create(array $config): self {
        return match ($config['db_connection_mode']) {
            'mysql' => new MySQLConnection($config),
            'pgsql' => new PgSQLConnection($config),
            default => throw new Exception('Unsupported database driver'),
        };
    }
}
