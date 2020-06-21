<?php

namespace App\Models;
use \Core\DB;
use \App\Models\Model;
use \Core\Session;
use \Core\Cookie;


class UserSessions extends Model{

    public $user_id='';

    public function __construct($user=''){
        $this->table='user_sessions';
        parent::__construct($this->table);
    }

   public function __get($name){
       return $this->$name;
   }












}