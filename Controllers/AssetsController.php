<?php

namespace Controllers;

use Core\Controller;

class AssetsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function viewAction() {
        echo "AssetsController view";
    }

    public function indexAction() {
        echo "AssetsController index";
    }
}


