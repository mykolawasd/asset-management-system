<?php

namespace Controllers;

use Core\Controller;

class NotFoundController extends Controller {


    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        return $this->render();
    }





    public function errorAction() {
        return $this->render(null, [
            'title' => '404 Not Found',
            'content' => '404 Not Found'
        ]);
    }



}


