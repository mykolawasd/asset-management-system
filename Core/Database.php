<?php

namespace Core;

require_once __DIR__ . '/../Config/database.php';

class Database {
    private \PDO $db;

    public function __construct() {
        try {
            $this->db = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            return $this->db;
        } catch (\PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }



    // private function connect() {
    //     $db = null;

    //    try {
    //     $db = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

    //    } catch (\PDOException $e) {

    //     die("Error: " . $e->getMessage());
    //    }
    //    return $db;

    // }

    /**
     * Query the database with automatic parameter type binding.
     * @param string $sql
     * @param array $params
     * @return \PDOStatement
     */
    public function query(string $sql, array $params = []): \PDOStatement {
        try {
            $stmt = $this->db->prepare($sql);
            // Перебираем переданные параметры и привязываем их с указанием типа
            foreach ($params as $key => $value) {
                $paramType = is_int($value) ? \PDO::PARAM_INT : \PDO::PARAM_STR;
                $stmt->bindValue($key, $value, $paramType);
            }
            $stmt->execute();
            return $stmt;
        } catch (\PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }



    // public function execute($sql, $params = []) {
    //     try {
    //         $stmt = $this->db->prepare($sql);
    //         $stmt->execute($params);
    //         return $stmt->rowCount();

    //     } catch (\PDOException $e) {
    //         die("Error: " . $e->getMessage());
    //     }


    }








