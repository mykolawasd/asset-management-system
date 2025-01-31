<?php

namespace Controllers;

use Core\Controller;


class AssetsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        return $this->render(null, [
            'title' => 'Assets View',
            'content' => 'Assets View'
        ]);
    }


    public function indexAction() {
        return $this->render(null, [
            'title' => 'Assets Index',
            'content' => 'Assets Index'
        ]);
    }


}


