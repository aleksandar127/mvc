<?php

namespace App\Controllers;

use Core\Router;
use \App\Models\Users;
use \Core\Input;
use \Core\Validate;

class Register extends Controller
{

  public function __construct($controller, $action)
  {
    $this->load_model('\App\Models\Users');
    parent::__construct($controller, $action);
    // $this->view->setLayout('default');

  }

  public function login()
  {

    $this->view->render('login');
  }

  public function loginAction()
  {
    
    $validation = new \Core\Validate();
    if ($_POST) {
      $validation->check($_POST, [
        'username' => [
          'display' => 'username',
          'required' => true,
        ],
        'password' => [
          'display' => 'password',
          'required' => true,
          'min' => '3',
        ],
      ]);
      if ($validation->passed()) {
        $user = new Users();
        $users = $user->findByUsername($_POST['username']); 
        $user->populateObjData($users);
        if ($user && password_verify(\Core\Input::get('password'), $user->password)) {
          $remember = (isset($_POST['remember_me'])) ? true : false;
          $user->login($remember);
          \Core\Router::redirect('home/index');
          exit();
        }
      }
    }
    $this->view->displayErrors = $validation->displayErrors();
    $this->view->render('login');
  }

  public function logout()
  {
    if (currentUser()) {
      currentUser()->logout();
    }
    \Core\Router::redirect('register/login');
  }

  public function registerAction()
  {
    $vaidation = new Validate();
    $posted_values = ['fname' => '', 'lname' => '', 'username' => '', 'mail' => '', 'password' => '', 're_password' => ''];
    if ($_POST) {
      $posted_values = posted_values($_POST);
      $vaidation->check($_POST, [
        'fname' => ['display' => 'First Name', 'required' => true],
        'lname' => ['display' => 'Last Name', 'required' => true],
        'username' => ['display' => 'Username', 'required' => true, 'min' => 5, 'unique' => true],
        'email' => ['display' => 'Email', 'required' => true, 'valid_email' => true],
        'password' => ['display' => 'Password', 'required' => true, 'min' => 5],
        're_password' => ['display' => 'Re_password', 'required' => true, 'matches' => 'password'],
      ]);
    }
    if ($vaidation->passed()) {
      $newUser = new Users();
      $newUser->registerNewUser($_POST);
      $newUser->login();
      Router::redirect('home/index');
      exit();
    }
    $this->view->data['post'] = $posted_values;
    $this->view->displayErrors = $vaidation->displayErrors();
    $this->view->render('register');
  }

  public function register()
  {
    $this->view->render('register');
  }
}
