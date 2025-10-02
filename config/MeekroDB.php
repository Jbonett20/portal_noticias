<?php
/*
MeekroDB - Simplified MySQL Database Access
*/

class MeekroDB {
    
    public static $host = 'localhost';
    public static $port = 3306;
    public static $dbName = '';
    public static $user = '';
    public static $password = '';
    public static $encoding = 'utf8mb4';
    
    protected static $instance;
    protected static $mysqliInstance;
    
    public static function get() {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function __construct() {
        if (!self::$mysqliInstance) {
            self::$mysqliInstance = new mysqli(
                self::$host, 
                self::$user, 
                self::$password, 
                self::$dbName, 
                self::$port
            );
            
            if (self::$mysqliInstance->connect_error) {
                throw new Exception('Error de conexión: ' . self::$mysqliInstance->connect_error);
            }
            
            self::$mysqliInstance->set_charset(self::$encoding);
        }
    }
    
    public static function query($query, ...$params) {
        $instance = self::get();
        
        // Preparar statement
        $stmt = self::$mysqliInstance->prepare($query);
        
        if (!$stmt) {
            throw new Exception('Error preparando consulta: ' . self::$mysqliInstance->error);
        }
        
        // Bind parameters si existen
        if (!empty($params)) {
            $types = '';
            $values = [];
            
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $values[] = $param;
            }
            
            $stmt->bind_param($types, ...$values);
        }
        
        $stmt->execute();
        
        if ($stmt->error) {
            throw new Exception('Error ejecutando consulta: ' . $stmt->error);
        }
        
        return $stmt;
    }
    
    public static function queryFirstRow($query, ...$params) {
        $stmt = self::query($query, ...$params);
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public static function queryAllRows($query, ...$params) {
        $stmt = self::query($query, ...$params);
        $result = $stmt->get_result();
        
        if ($result) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        return [];
    }
    
    public static function insert($table, $data) {
        $fields = array_keys($data);
        $values = array_values($data);
        
        $fieldList = '`' . implode('`, `', $fields) . '`';
        $placeholders = str_repeat('?,', count($values) - 1) . '?';
        
        $query = "INSERT INTO `{$table}` ({$fieldList}) VALUES ({$placeholders})";
        
        self::query($query, ...$values);
        
        return self::$mysqliInstance->insert_id;
    }
    
    public static function update($table, $data, $where = '', ...$whereParams) {
        $setParts = [];
        $values = [];
        
        foreach ($data as $field => $value) {
            $setParts[] = "`{$field}` = ?";
            $values[] = $value;
        }
        
        $setClause = implode(', ', $setParts);
        $query = "UPDATE `{$table}` SET {$setClause}";
        
        if ($where) {
            $query .= " WHERE {$where}";
            $values = array_merge($values, $whereParams);
        }
        
        $stmt = self::query($query, ...$values);
        
        return self::$mysqliInstance->affected_rows;
    }
    
    public static function delete($table, $where = '', ...$whereParams) {
        $query = "DELETE FROM `{$table}`";
        
        if ($where) {
            $query .= " WHERE {$where}";
        }
        
        $stmt = self::query($query, ...$whereParams);
        
        return self::$mysqliInstance->affected_rows;
    }
    
    public static function count($table, $where = '', ...$whereParams) {
        $query = "SELECT COUNT(*) as total FROM `{$table}`";
        
        if ($where) {
            $query .= " WHERE {$where}";
        }
        
        $result = self::queryFirstRow($query, ...$whereParams);
        
        return $result ? $result['total'] : 0;
    }
    
    public static function insertId() {
        return self::$mysqliInstance->insert_id;
    }
    
    public static function affectedRows() {
        return self::$mysqliInstance->affected_rows;
    }
    
    public static function startTransaction() {
        return self::$mysqliInstance->autocommit(false);
    }
    
    public static function commit() {
        $result = self::$mysqliInstance->commit();
        self::$mysqliInstance->autocommit(true);
        return $result;
    }
    
    public static function rollback() {
        $result = self::$mysqliInstance->rollback();
        self::$mysqliInstance->autocommit(true);
        return $result;
    }
}

// Alias para compatibilidad
class DB extends MeekroDB {}
?>