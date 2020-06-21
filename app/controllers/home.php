<?php

namespace App\Controllers;

use \App\Models\Users;

class Home extends Controller
{

    public function __construct($controller, $action)
    {

        parent::__construct($controller, $action);
    }

    public function index()
    {

        $user = new Users();
        $this->view->data = $user->find(['condition' => 'username=?', 'bind' => 'Aleksandar']);
        $this->view->render('home');
    }
}
