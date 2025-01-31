<?php

namespace Core;

class Core {
    private static $instance = null;
    public $app;

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
    }


    public function run() {
        $route = @($_GET['route'] ?? '') ;
        $route = explode('/', $route);
        $controllerName = array_shift($route);
        $actionName = array_shift($route);
        $controller = '\\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $action = $actionName . 'Action';

        if (!class_exists($controller)) {
            $controller = '\\Controllers\\NotFoundController';
        }
        if (!method_exists($controller, $action)) {
            $action = 'indexAction';
        }

        $this->app['controller'] = $controller; 
        $this->app['action'] = $action;

        $controller = new $controller();
        $controller->$action();

    }



    public function done() { }
}