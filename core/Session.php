<?php

namespace Core;

class Session{

public static function exists($name){
    return (isset($_SESSION[$name]))?true:false;
}

public static function get($name){
    return $_SESSION[$name];
}

public static function set($name,$val){
    return $_SESSION[$name]=$val;
}

public static function delete($name){
    if(self::exists($name))
        unset($_SESSION[$name]);
}

public static function uagent_no_version(){
    $ua=$_SERVER['HTTP_USER_AGENT'];
    $regx='/\/[a-zA-Z0-9.]+/';
    $ua=preg_replace($regx,'',$ua);
    return $ua;
}



}