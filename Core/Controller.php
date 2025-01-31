<?php

namespace Core;

class Controller {
    protected $viewPath;

    public function __construct() {
        $controller = Core::getInstance()->app['controller'];
        $action = Core::getInstance()->app['action'];
        $this->viewPath = "Views/" . $controller . "/" . $action . ".php";
    }

    


}