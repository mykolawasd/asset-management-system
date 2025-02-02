<?php

namespace Models;

use \Core\Core;
use \PDO;
enum Role: string {
    case ADMIN = 'admin';
    case USER = 'user';
}


class User {
    public string $username;
    public string $password;
    public Role $role;


    public function __construct(string $username, string $password, Role $role = Role::USER) {
        $this->username = $username;
        $this->password = $password;
        $this->role = $role;
    }



    public function create(): void { 
        $sql = "INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)";

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);
        $params = [
            'username' => $this->username,
            'password_hash' => $password_hash,
            'role' => $this->role->value
        ];

        Core::$db->query($sql, $params);
    }


    public static function exists(string $username): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = ['username' => $username];
        return Core::$db->query($sql, $params)->fetchColumn() > 0;
    }

    
    public static function authenticate(string $username, string $password): bool {
        $sql = "SELECT * FROM users WHERE username = :username";
        $params = ['username' => $username];
        $user = Core::$db->query($sql, $params)->fetch(PDO::FETCH_ASSOC);


        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password_hash'])) {
            return false;
        }

        
        $_SESSION['user'] = $user;
        return true;
    }

    public static function logout(): void {
        unset($_SESSION['user']);
    }

}