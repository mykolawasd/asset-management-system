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
        self::$db = new Database();
    }



    public function run() {
        $route = @($_GET['route'] ?? '');
        $route = explode('/', $route);
        $controllerName = array_shift($route);
        $actionName = array_shift($route);
        
        $this->app['controller'] = ucfirst($controllerName);
        $this->app['action'] = $actionName;

        $controller = '\\Controllers\\' . ucfirst($controllerName) . 'Controller';
        $action = $actionName . 'Action';

        if (!class_exists($controller)) {
            $controller = '\\Controllers\\NotFoundController';
            $this->app['controller'] = 'NotFound';
            $this->app['action'] = 'index';
        } else if (!method_exists($controller, $action)) {
            $this->app['action'] = 'index';
        }

        if ($this->app['action'] === 'index' || $this->app['action'] === '') {
            $action = 'indexAction';
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