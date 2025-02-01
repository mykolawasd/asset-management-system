<?php

namespace Controllers;

use Core\Controller;
use Models\User;

class UsersController extends Controller {


    public function __construct() {
        parent::__construct();
    }


    public function registerAction() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            $password2 = $_POST['password2'];

            if (User::exists($username)) {
                $errors['username'][] = 'Username ' . $username . ' already exists';
            }

            if (strlen($username) < 3) {
                $errors['username'][] = 'Username must be at least 3 characters long';
            }
            

            if ($password !== $password2) {
                $errors['password'][] = 'Passwords do not match';
            }

            if (strlen($password) < 8) {
                $errors['password'][] = 'Password must be at least 8 characters long';
            }

            if (!preg_match("#[0-9]+#", $password)) {
                $errors['password'][] = 'Password must contain at least one number';
            }

            if (!preg_match("#[a-z]+#", $password)) {
                $errors['password'][] = 'Password must contain at least one lowercase letter';
            }

            if (!preg_match("#[A-Z]+#", $password)) {
                $errors['password'][] = 'Password must contain at least one uppercase letter';
            }


            if (count($errors) > 0) {
                return $this->render(null, ['errors' => $errors]);
            }
            else {
                $user = new User($username, $password);
                $user->create();
                return $this->render('Views/Users/register-done.php');
            }
            



        } 
        else {
            return $this->render();
        }
    }


}