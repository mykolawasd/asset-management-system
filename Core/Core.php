<?php

namespace Core;

class Core {
    private static ?Core $instance = null;

    public static ?Database $db = null;

    public array $app;

    private function __construct() {
        $this->app =[];
    }
   

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init() { 
        session_start();
        self::$db = new Database();
    }



    public function run() {
        $route = @($_GET['route'] ?? '');
        $route = explode('/', $route);
        $controllerName = array_shift($route);
        $actionName = array_shift($route);

        if (empty($controllerName) && empty($actionName)) {
            $controllerName = 'Main';
            $actionName = 'index';
        }

        
        $this->app['controller'] = ucfirst($controllerName);
        $this->app['action'] = $actionName;

        $controller = '\\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $action = $actionName . 'Action';

        if (!class_exists($controller) ) {
            $controller = '\\Controllers\\MainController';
            $action = 'errorAction';
            $this->app['controller'] = 'Main';
            $this->app['action'] = 'error';

        } 
        else if (!method_exists($controller, $action)) {
            $action = 'indexAction';
            $this->app['action'] = 'index';
        }


        $controller = new $controller();

        $this->app['actionResult'] = $controller->$action();
    }




    public function done() {
        $layout = 'Themes/Light/Layout.php';
        $template = new Template($layout);

        
        $template->setParam('content', $this->app['actionResult']);

        $html = $template->getHTML();
        echo $html;
     }


}