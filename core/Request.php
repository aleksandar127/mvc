<?php

namespace Core;

class Request{

public $get=array();
public $post=array();
public $controller;
public $method;

public function __construct(){

    $url= $_SERVER['REQUEST_URI'];
    $parts_of_url = explode('/', $url);
    array_shift($parts_of_url);
    $this->post_request();
    $this->controller=ucfirst($parts_of_url[1]);
    $this->method=isset($parts_of_url[2])?$parts_of_url[2]:'index';
     
}


	private function post_request()
	{
		foreach ($_POST as $name => $value) {
			$this->post[$name] = $value;
		}
		
	}






}