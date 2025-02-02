<?php

namespace Controllers;

use Core\Controller;

class MainController extends Controller {


    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        return $this->render();
    }





    public function errorAction() {
        return $this->render('Views/404.php', [
            'title' => '404 Not Found',
        ]);
    }




}


