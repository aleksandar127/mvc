<?php 
namespace App\Controllers;
use \App\Models\Model;
use \Core\Session;
use \Core\Input;
use Core\Router;
use \App\Models\Users;
use \Core\Validate;

class Restricted extends Controller{


public function __construct($controller,$action){
  
  parent::__construct( $controller,$action);
 

}


public function index(){
    return $this->view->render('index');
}


}