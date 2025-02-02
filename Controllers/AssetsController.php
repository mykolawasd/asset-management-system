<?php

namespace Controllers;

use Core\Controller;
use Core\Core;

class AssetsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        Core::getInstance()->app['title'] = 'Assets View';
        return $this->render(null, [
            'title' => 'Assets View',
            'content' => 'Assets View'
        ]);
    }


    public function indexAction() {
        Core::getInstance()->app['title'] = 'Assets Index';
        return $this->render(null, [
            'title' => 'Assets Index',
            'content' => 'Assets Index'
        ]);
    }

    

}


