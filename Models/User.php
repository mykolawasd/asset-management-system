<?php

namespace Models;


enum Role: string {
    case ADMIN = 'admin';
    case USER = 'user';
}


class User {
    public string $username;
    public string $password_hash;
    public Role $role;


    public function __construct(string $username, string $password_hash, Role $role = Role::USER) {
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->role = $role;
    }


    public function create(): void { 
        $sql = "INSERT INTO users (username, password_hash, role) VALUES (:username, :password_hash, :role)";
        $params = [
            'username' => $this->username,
            'password_hash' => $this->password_hash,
            'role' => $this->role->value
        ];
        

        \Core\Core::$db->query($sql, $params);
    }

    public static function exists(string $username): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $params = ['username' => $username];
        return \Core\Core::$db->query($sql, $params)->fetchColumn() > 0;
    }
    
}