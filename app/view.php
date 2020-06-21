<?php

namespace App;


class View{

public $data = array();
public $displayErrors;

protected $head,$body,$ob;

public function render($file){

    include('./app/views/'.$file.'.php');
    include('./app/views/layout.php');
}

public function content($tag){

    return $this->{$tag};

}

public function start($tag){

    $this->ob=$tag;
    ob_start();
    
}

public function end($tag){

    $this->{$tag}=ob_get_clean();

}
    






}