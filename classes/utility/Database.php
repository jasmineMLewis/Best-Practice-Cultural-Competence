<?php

class Database {

    private static $_instance = null;
    private $_count = 0;
    private $_error = false;
    private $_pdo;
    private $_query;
    private $_results;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=localhost;dbname=cultural_competence', 'root', '');
        } catch (PDOException $e) {
            die("Error connecting to database");
        }
    }

    private function action($action, $table, $where = array()) {
        if (count($where) === 3) {
            $operators = array("=", ">", "<", ">=", "<=");
            $field = "$where[0]";
            $operator = "$where[1]";
            $value = "$where[2]";

            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} "
                        . "WHERE {$field} {$operator} ?";

                if (!$this->query($sql, array($value))->getError()) {
                    return $this;
                }
            }
        }
        return false;
    }

    public function delete($table, $where) {
        return $this->action("DELETE", $table, $where);
    }

    public function getCount() {
        return $this->_count;
    }

    public function getError() {
        return $this->_error;
    }

    public function getFirstResult() {
        $data = $this->getResults();
        if (isset($data[0])) {
            return $data[0];
        }
    }

    public static function getInstance() {
        if (!isset(self::$_instance)) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }

    public function getResults() {
        return $this->_results;
    }

    public function fetchColumnNames($table) {
        $query = $this->_pdo->prepare("DESCRIBE $table");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_COLUMN);
    }

    public function insert($table, $fields = array()) {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = null;
            $fieldParams = 1;

            foreach ($fields as $field) {
                $values .= "?";
                if ($fieldParams < count($fields)) {
                    $values .= ', ';
                }
                $fieldParams++;
            }

            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) "
                    . "VALUES ({$values})";
           // echo $sql;
           // echo '<br/>';
            if ($this->query($sql, $fields)->getError()) {
                return true;
            }
        }
        return false;
    }

    public function lastInsertId() {
        return $this->_pdo->lastInsertId();
    }

    public function query($sql, $params = array()) {
        $this->_error = false;
        $this->_query = $this->_pdo->prepare($sql);
        $countParams = 1;

        if (count($params)) {
            foreach ($params as $param) {
                $this->_query->bindValue($countParams, $param);
                $countParams++;
            }
        }

        if ($this->_query->execute()) {
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
            $this->_count = $this->_query->rowCount();
        } else {
            $this->_error = true;
        }
        return $this;
    }

    public function select($table, $where) {
        return $this->action("SELECT *", $table, $where);
    }

    public function updateMulitipleWhere($table, $field, $id, $field2, $id2, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE ({$field} = '{$id}' AND {$field2} = {$id2})";
        if ($this->query($sql, $fields)->getError()) {
            return true;
        }
        return false;
    }

    public function update($table, $field, $id, $fields) {
        $set = '';
        $x = 1;

        foreach ($fields as $name => $value) {
            $set .= "{$name} = ?";

            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }

        $sql = "UPDATE {$table} SET {$set} WHERE {$field} = '{$id}'";
        if ($this->query($sql, $fields)->getError()) {
            return true;
        }
        return false;
    }

}
