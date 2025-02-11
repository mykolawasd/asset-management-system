<?php

namespace Controllers;

use Core\Controller;
use Core\Core;

class MainController extends Controller {


    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        Core::getInstance()->app['title'] = 'Home';
        return $this->render();
    }






    public function errorAction() {
        Core::getInstance()->app['title'] = '404 Not Found';
        return $this->render('views/404.php', [
            'title' => '404 Not Found',
        ]);
    }




}


