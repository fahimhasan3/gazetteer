<?php

require_once(__DIR__ . "/DatabaseConnection.php");

class PgSQLConnection extends DatabaseConnection {
    protected $query_result;
    protected $last_result;
    protected $last_statement_name;
    protected $show_errors = true;

    protected function connect() {
        $connectionString = sprintf(
            "host=%s port=%s dbname=%s user=%s password=%s",
            $this->config['dbhost'],
            $this->config['dbport'] ?? 5432,
            $this->config['dbname'],
            $this->config['dbuser'],
            $this->config['dbpass']
        );

        $this->connection = pg_connect($connectionString);

        if (!$this->connection) {
            $this->error('PostgreSQL connection failed');
        }
    }

    public function query(string $query, ...$params) {
        $this->query_count++;

        // Convert ? placeholders to $1, $2, etc. for PostgreSQL
        $count = 0;
        $query = preg_replace_callback('/\?/', function () use (&$count) {
            $count++;
            return '$' . $count;
        }, $query);

        if (empty($params)) {
            $this->last_result = pg_query($this->connection, $query);
        } else {
            $this->last_statement_name = 'stmt_' . uniqid();
            $prepared = pg_prepare($this->connection, $this->last_statement_name, $query);

            if (!$prepared) {
                $this->error("Failed to prepare query: " . pg_last_error($this->connection));
            }

            $this->last_result = pg_execute($this->connection, $this->last_statement_name, $params);
        }

        if (!$this->last_result) {
            $this->error("Query execution failed: " . pg_last_error($this->connection));
        }

        return $this;
    }

    public function fetchAll() {
        return pg_fetch_all($this->last_result) ?: [];
    }

    public function fetchOne() {
        return pg_fetch_assoc($this->last_result) ?: null;
    }

    public function affectedRows(): int {
        return pg_affected_rows($this->last_result);
    }

    public function lastInsertId() {
        $row = pg_fetch_assoc($this->last_result);
        return $row ? reset($row) : null;
    }

    public function close() {
        if ($this->connection) {
            pg_close($this->connection);
            $this->connection = null;
        }
    }

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }
}
