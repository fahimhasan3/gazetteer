<?php

require_once(__DIR__ . "/DatabaseConnection.php");

class MySQLConnection extends DatabaseConnection {
    protected $query;
    protected $query_closed = true;
    protected $show_errors = true;
	public $query_count = 0;

    protected function connect() {
        $this->connection = new mysqli(
            $this->config['dbhost'],
            $this->config['dbuser'],
            $this->config['dbpass'],
            $this->config['dbname']
        );

        if ($this->connection->connect_error) {
            $this->error('MySQL connection failed: ' . $this->connection->connect_error);
        }

        $this->connection->set_charset('utf8');
    }

    public function query($query, ...$params) {
        if (!$this->query_closed && $this->query) {
            $this->query->close();
        }

        if ($this->query = $this->connection->prepare($query)) {
            if (!empty($params)) {
                $types = '';
                $args_ref = [];

                foreach ($params as &$param) {
                    $types .= $this->_gettype($param);
                    $args_ref[] = &$param;
                }

                array_unshift($args_ref, $types);
                call_user_func_array([$this->query, 'bind_param'], $args_ref);
            }

            $this->query->execute();

            if ($this->query->errno) {
                $this->error('MySQL query error: ' . $this->query->error);
            }

            $this->query_closed = false;
            $this->query_count++;
            return $this;
        } else {
            $this->error('MySQL prepare error: ' . $this->connection->error);
        }
    }

    public function fetchAll($callback = null) {
	    $params = array();
        $row = array();
	    $meta = $this->query->result_metadata();
	    while ($field = $meta->fetch_field()) {
	        $params[] = &$row[$field->name];
	    }
	    call_user_func_array(array($this->query, 'bind_result'), $params);
        $result = array();
        while ($this->query->fetch()) {
            $r = array();
            foreach ($row as $key => $val) {
                $r[$key] = $val;
            }
            if ($callback != null && is_callable($callback)) {
                $value = call_user_func($callback, $r);
                if ($value == 'break') break;
            } else {
                $result[] = $r;
            }
        }
        $this->query->close();
        $this->query_closed = TRUE;
		return $result;
	}

    public function fetchOne() {
        $params = array();
        $row = array();
        $meta = $this->query->result_metadata();
    
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
    
        call_user_func_array(array($this->query, 'bind_result'), $params);
    
        $result = null;
        while ($this->query->fetch()) {
            $record = array();
            foreach ($row as $key => $val) {
                $record[$key] = $val;
            }
            $result = $record;
        }
    
        $this->query->close();
        $this->query_closed = TRUE;
    
        return $result;
    }

	public function close() {
		return $this->connection->close();
	}

    public function numRows() {
		$this->query->store_result();
		return $this->query->num_rows;
	}

	public function affectedRows() {
		return $this->query->affected_rows;
	}

    public function lastInsertID() {
    	return $this->connection->insert_id;
    }

    public function error($error) {
        if ($this->show_errors) {
            exit($error);
        }
    }

	private function _gettype($var) {
	    if (is_string($var)) return 's';
	    if (is_float($var)) return 'd';
	    if (is_int($var)) return 'i';
	    return 'b';
	}
}
