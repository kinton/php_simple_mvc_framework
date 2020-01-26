<?php

class Controller
{

    public function __construct()
    {
        $this->view = new View();
    }

    public function loadModel($name)
    {
        $path = 'models/' . $name . '_model.php';
        if (file_exists($path)) {
            require 'models/' . $name . '_model.php';
            $modelName = $name . '_Model';
            $this->model = new $modelName();
            
            //$this->view->navData = $this->model->getNavData();
            $this->view->siteConstants = $this->model->getSiteConstants(SITE_CONSTANT_LIST);
        } else {
            $this->model = new Model();
        }
    }
}

?>