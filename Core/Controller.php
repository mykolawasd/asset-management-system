<?php

namespace Core;

abstract class Controller {
    protected $viewPath;

    public function __construct() {

        $controller = Core::getInstance()->app['controller'];
        $action = Core::getInstance()->app['action'];
        $this->viewPath = "Views/" . $controller . "/" . $action . ".php";
        
    }

    public function render($viewPath = null, $data = null) {
        $viewPath = $viewPath ?? $this->viewPath;
        $template = new Template($viewPath);
        if (!empty($data)) {
            //var_dump($data);
            $template->setParams($data);
        }
        $html = $template->getHTML();
        return $html;

    }

}