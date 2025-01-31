<?php

namespace Core;

class Template {
    protected $viewPath;
    protected $params;

    public function __construct($viewPath) {
        $this->viewPath = $viewPath;
        $this->params = [];
    }

    public function getHTML() {

        ob_start();
        // var_dump($this->params);
        
        extract($this->params);
        
        include $this->viewPath;
        $html = ob_get_contents();
        ob_end_clean();
        return $html;




    }

    public function setParam($key, $value) {
        $this->params[$key] = $value;
    }


    public function setParams($params) {
        foreach ($params as $key => $value) {
            // var_dump($key, $value);
            $this->setParam($key, $value);
        }
    }


}