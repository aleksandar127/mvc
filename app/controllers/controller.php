<?php

namespace App\Controllers;
use \core\DB;
use \App\View;
use \App\Models\Users;

class Controller{

protected $controller,$action;
public $view;
public function __construct($controller,$action){

    $this->controller=$controller;
    $this->action=$action;
    $this->view= new \App\View();

}

protected function load_model($model){
    if(class_exists($model)){
        $modelName=explode('\\',$model);
        $modelName=end($modelName);
        $this->{$modelName.'Model'}=new $model();
        
    }

}














}