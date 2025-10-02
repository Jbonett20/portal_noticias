<?php
require_once __DIR__ . '/MeekroDB.php';

class Database {
    private static $instance = null;
    
    public function __construct() {
        // Configurar MeekroDB
        MeekroDB::$host = DB_HOST;
        MeekroDB::$dbName = DB_NAME;
        MeekroDB::$user = DB_USER;
        MeekroDB::$password = DB_PASS;
        MeekroDB::$encoding = 'utf8mb4';
        
        try {
            // Inicializar conexión
            MeekroDB::get();
        } catch (Exception $e) {
            die('Error de conexión: ' . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function query($sql, $params = []) {
        try {
            if (empty($params)) {
                return MeekroDB::query($sql);
            } else {
                return MeekroDB::query($sql, ...$params);
            }
        } catch (Exception $e) {
            throw new Exception('Error en consulta: ' . $e->getMessage());
        }
    }
    
    public function fetch($sql, $params = []) {
        try {
            if (empty($params)) {
                return MeekroDB::queryFirstRow($sql);
            } else {
                return MeekroDB::queryFirstRow($sql, ...$params);
            }
        } catch (Exception $e) {
            throw new Exception('Error en consulta: ' . $e->getMessage());
        }
    }
    
    public function fetchAll($sql, $params = []) {
        try {
            if (empty($params)) {
                return MeekroDB::queryAllRows($sql);
            } else {
                return MeekroDB::queryAllRows($sql, ...$params);
            }
        } catch (Exception $e) {
            throw new Exception('Error en consulta: ' . $e->getMessage());
        }
    }
    
    public function insert($table, $data) {
        try {
            return MeekroDB::insert($table, $data);
        } catch (Exception $e) {
            throw new Exception('Error en inserción: ' . $e->getMessage());
        }
    }
    
    public function update($table, $data, $where = '', ...$whereParams) {
        try {
            return MeekroDB::update($table, $data, $where, ...$whereParams);
        } catch (Exception $e) {
            throw new Exception('Error en actualización: ' . $e->getMessage());
        }
    }
    
    public function delete($table, $where = '', ...$whereParams) {
        try {
            return MeekroDB::delete($table, $where, ...$whereParams);
        } catch (Exception $e) {
            throw new Exception('Error en eliminación: ' . $e->getMessage());
        }
    }
    
    public function count($table, $where = '', ...$whereParams) {
        try {
            return MeekroDB::count($table, $where, ...$whereParams);
        } catch (Exception $e) {
            throw new Exception('Error en conteo: ' . $e->getMessage());
        }
    }
    
    public function lastInsertId() {
        return MeekroDB::insertId();
    }
    
    public function beginTransaction() {
        return MeekroDB::startTransaction();
    }
    
    public function commit() {
        return MeekroDB::commit();
    }
    
    public function rollback() {
        return MeekroDB::rollback();
    }
}
?>