<?php

namespace Controllers;

use Core\Controller;

class NotFoundController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function indexAction() {
        echo "NotFoundController index";
    }


    public function viewAction() {
        echo "NotFoundController view";
    }
}


