<?php

namespace Core;

require_once __DIR__ . '/../Config/database.php';

class Database {
    

    private function connect() {
        $db = null;
       try {
        $db = new \PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);

       } catch (\PDOException $e) {

        die("Error: " . $e->getMessage());
       }
       return $db;

    }

    
    public function query($sql, $params = []) {
        try {
            $db = $this->connect();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Error: " . $e->getMessage());
        }
    }

    public function execute($sql, $params = []) {
        try {
            $db = $this->connect();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            die("Error: " . $e->getMessage());
        }

    }







}